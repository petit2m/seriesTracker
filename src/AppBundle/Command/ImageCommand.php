<?php

namespace AppBundle\Command;

use AppBundle\Entity\Serie;
use AppBundle\Entity\Image;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

    
class ImageCommand extends BaseCommand
{
    protected $em;
    protected $serviceTvdb;
    protected $serviceTmdb;
    const MAX_SERIES = 50;
    
    protected function configure()
    {
        $this
           ->setName('sync:images')
           ->setDescription('Enregistre en base les images provenant des diverses sources de media')
           ->addArgument('mediaType', InputArgument::REQUIRED, 'Le type de media à mettre à jour : Serie|Season|Episode|Actor')
           ->addArgument('slug', InputArgument::OPTIONAL, 'Le slug du media à mettre à jour ');
    }
    
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
       $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
       $this->serviceFanart = $this->getContainer()->get('serviceFanart');
       $this->serviceTmdb = $this->getContainer()->get('serviceTmdb');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment> Démarrage de la mise à jour des images </comment>");
        
        $mediaType = $input->getArgument('mediaType');
        if (!in_array($mediaType, array('Serie','Season','Episode','Actor'))) {
            $output->writeln("<error> 'Unknown media type !' </error>");
            exit();
        }
            
        $slug = $input->getArgument('slug');
        if (null !== $slug) {
            $item = $this->em->getRepository("AppBundle:$mediaType")->findOneBySlug($slug);
            if (!$item) {
                $output->writeln("<error>Impossible de trouver $slug</error>\n\n\n\n");
                exit();
            }
            $this->update($item, $mediaType);            
        }else{
            $collection = $this->em->getRepository("AppBundle:$mediaType")->findAll();    
            $nbFound = count($collection);
            $output->writeln("<info> $nbFound $mediaType trouvées</info>\n\n\n\n");
            $bar = $this->getProgressBar($nbFound, 'Mise à jour en cours');        
            foreach($collection as $item){
                  $bar->advance();    
                  $this->update($item, $mediaType);
            }
            $bar->setMessage('Terminé', 'title');
            $bar->finish();
            $output->writeln("\n<comment> Fin de la mise à jour</comment>");
        }
    }
    /**
     * Ajoute la série en bdd si elle n'existe pas, on 
     *
     * @param string $id identifiant serviio (unique pour une instance du serveur Serviio)
     * @param string $name nom de la série
     * @return void
     * @author Niko
     */
    private function update($item,$mediaType)
    {
        $images = $this->getImages($item,$mediaType);
        if(empty($images)) {
            return;
        }
        $object = $this->em->getRepository("AppBundle:$mediaType")->findOneById($item->getId());
        $this->insertImage($object,$images);
    }    
      
    protected function getImages($item, $mediaType)
    {
        switch($mediaType){
            case 'Serie':
                $tmdbImages = $this->serviceTmdb->getImages($item->getIdTmdb());
                $tvdbImages = $this->serviceFanart->getImages($item->getIdTvdb());
                return array_merge($tmdbImages,$tvdbImages);
                break;
            case 'Season':
                return $this->serviceTmdb->getImages($item->getSerie()->getIdTmdb(),$item->getNumber());
                break;
            case 'Episode':
                return $this->serviceTmdb->getImages($item->getSeason()->getSerie()->getIdTmdb(),$item->getSeason()->getNumber(),$item->getNumber());
                break;
            case 'Actor':
                return $this->serviceTmdb->getActorImages($item->getIdTmdb());
                break;
              }
    }  
    
    protected function insertImage($entite, $images)
    {
        foreach ($images as $item) {
            $image = new Image();
            $image->setType($item['type'])
                ->setFormat($item['format'])
                ->setUrl($item['url']);
            $this->em->persist($image);                
            $entite->addImage($image);
        }
        $this->em->persist($entite);  
        $this->em->flush(); 
    }          
}    