<?php

namespace AppBundle\Command;

use AppBundle\Entity\Serie;
use AppBundle\Entity\Image;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

    
class SerieCommand extends BaseCommand
{

    protected $em;
    protected $serviceTrackt;
    const MAX_SERIES = 1;
    
    protected function configure()
    {
        $this
           ->setName('sync:serie')
           ->setDescription('Enregistre en base les series présentes sur trakt');
        //   ->addArgument('name', InputArgument::OPTIONAL, 'Qui voulez vous saluer??')
        //   ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules');
    }
    
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
       $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
       $this->serviceTrackt = $this->getContainer()->get('serviceTrackt');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment> Démarrage de la mise à jour des séries </comment>");
        $collection = $this->serviceTrackt->getMySeries();
        $nbFound = count($collection);
        $output->writeln("<info> ".$nbFound." series trouvées</info>\n\n\n\n");
        $bar = $this->getProgressBar($nbFound, 'Mise à jour en cours');
        $counter = 0;
        foreach ($collection as $serie) {
            if(isset($histo[$serie['show']['ids']['trakt']]))
                continue;
            
            if($this->refreshSerie($serie['show']['ids']['trakt'])){
                if(++$counter > self::MAX_SERIES)
                    break;
            }
                
            $bar->advance();           
            $histo[$serie['show']['ids']['trakt']]=true;
        }
        
        $bar->setMessage('Terminé', 'title');
        
        if($counter <= self::MAX_SERIES)
            $bar->finish();
        
        $output->writeln("\n<comment> Fin de la mise à jour</comment>");
       
     
    }
    /**
     * Ajoute la série en bdd si elle n'existe pas, on 
     *
     * @param string $id identifiant serviio (unique pour une instance du serveur Serviio)
     * @param string $name nom de la série
     * @return void
     * @author Niko
     */
    private function refreshSerie($id)
    {
        $serie = $this->em->getRepository('AppBundle:Serie')->findExisting($id);
       
        if(!$serie){
            $info = $this->serviceTrackt->getTranslatedInfos($id); 
            $this->addSerie($info);
            
            return true;
        }
        
        return false;
    }    
        
    private function addSerie($info)
    {
        $serie = new Serie();
        $serie->setIdTrakt($info['ids']['trakt'])
              ->setIdTvdb($info['ids']['tvdb'])
              ->setSlug($info['ids']['slug'])
              ->setTitle($info['title'])
              ->setYear($info['year'])
              ->setFirstAired(new \DateTime($info['first_aired']))
              ->setNetwork($info['network'])
              ->setRuntime($info['runtime'])
              ->setAirDay($info['airs']['day'])
              ->setAirTime($info['airs']['time'])
              ->setSummary($info['overview'])
              ->setUpdatedAt(new \DateTime($info['updated_at']))
              ->setStatus($info['status'])
              ->setRating($info['rating']); 
        
        $this->insertImage($serie,$info['images']);
        $this->persistAndSave($serie);
    }
    
  
}    
