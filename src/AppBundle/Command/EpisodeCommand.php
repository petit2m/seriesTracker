<?php

namespace AppBundle\Command;

use AppBundle\Entity\Episode;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

    
class EpisodeCommand extends BaseCommand
{

    protected $em;
    protected $serviceTrackt;
    const MAX_EPISODES = 150;
    
    protected function configure()
    {
        $this
           ->setName('sync:episode')
           ->setDescription('Enregistre en base les épisodes de trakt');
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
        $output->writeln("<comment> Démarrage de la mise à jour des saisons </comment>");
        $seasons = $this->em->getRepository('AppBundle:Season')->findAll();       
        $nbFound = count($seasons);
        $output->writeln("<info> ".$nbFound." saisons trouvées</info>\n\n\n\n");
        $bar = $this->getProgressBar($nbFound, 'Mise à jour en cours');
        $counter = 0;
        
        foreach ($seasons as $season) {
            $episodes = $this->serviceTrackt->getEpisodes($season->getSerie()->getSlug(),$season->getNumber(),'full,images');

            foreach($episodes as $episode){
                if($this->checkEpisode($season,$episode) && ++$counter > self::MAX_EPISODES)
                        break 2;
            }
            
            $bar->advance();        
        }
        
        $this->em->flush();
        $bar->setMessage('Terminé', 'title');

        if($counter <= self::MAX_EPISODES)
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
    private function checkEpisode($season,$info)
    {
        $episode = $this->em->getRepository('AppBundle:Episode')->findByIdTrakt($info['ids']['trakt']);
       
        if(!$episode){
            $episode = $this->createEpisode($info);
            $season->addEpisode($episode);
            $this->em->persist($season);
            $this->em->persist($episode);  
            
            return true;
        }
        return false;
    }    
        
    private function createEpisode($info)
    {
        $episode = new Episode();
        $episode->setIdTrakt($info['ids']['trakt'])
              ->setIdTvdb($info['ids']['tvdb'])
              ->setSummary($info['overview'])
              ->setNumber($info['number'])
              ->setAired(new \DateTime($info['first_aired']))
              ->setTitle($info['title'])
              ->setRating($info['rating']); 
        
        $this->insertImage($episode,$info['images']);  
        
        return $episode;
    }
}    
