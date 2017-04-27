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
           ->addArgument('mediaType', InputArgument::REQUIRED, 'Le type de media à mettre à jour : Serie|Season|Episode')
           ->addArgument('slug', InputArgument::OPTIONAL, 'Le slug du media à mettre à jour ');
    }
    
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
       $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
       $this->serviceTvdb = $this->getContainer()->get('serviceRestTvdb');
       $this->serviceTmdb = $this->getContainer()->get('serviceTmdb');
       $this->tmdbConf = $this->serviceTmdb->getConfiguration(); // A ne récupérer qu'une fois
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment> Démarrage de la mise à jour des images </comment>");
        
        $mediaType = $input->getArgument('mediaType');
        if(!in_array($mediaType, array('Serie','Season','Episode'))){
            $output->writeln("<error> 'Unknown media type !' </error>");;
            exit();
        }
            
        $slug = $input->getArgument('slug');
        if(null !== $slug){

            $item = $this->em->getRepository("AppBundle:$mediaType")->findOneBySlug($slug);
            if(!$item){
                $output->writeln("<info>Impossible de trouver $slug</info>\n\n\n\n");
                exit();
            }

            $this->update($item->getId(),$mediaType);
        }else{
            $collection = $this->em->getRepository("AppBundle:$mediaType")->findAll();    
            $nbFound = count($collection);
            $output->writeln("<info> $nbFound $mediaType trouvées</info>\n\n\n\n");
            $bar = $this->getProgressBar($nbFound, 'Mise à jour en cours');        
            foreach($collection as $item){
                  $bar->advance();    
                  $this->update($item,$mediaType);
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
    private function update($id,$mediaType)
    {
        $item = $this->em->getRepository("AppBundle:$mediaType")->findBy($id);
        //$this->insertImage($item,$info['images']);
    }    
      
    protected function getImage($item)
    {
        # code...
    }        
    /*
Poster & Fanart
The highest rated poster and fanart are imported from TMDB. 1080p (or larger) is preferred for fanart and we fall back to 720p if nothing better is available. If no image exists on TMDB, we import from TVDB with the same 1080p preference.
Season Posters
The highest rated season posters are imported from TMDB. If no season poster exists, we import from TVDB.
Episode Screenshots
The highest rated episode screenshots are imported from TMDB since they support 1080p (and higher) resolutions. If no screenshot exists on TMDB, we import from TVDB.
Additional Images
Supporting TV show images such as logo, banner, and thumb are imported from Fanart.tv. If the no banner exists, we import from TVDB.
    */
    
      
}    
