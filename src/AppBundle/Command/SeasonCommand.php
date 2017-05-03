<?php

namespace AppBundle\Command;

use AppBundle\Entity\Serie;
use AppBundle\Entity\Season;
use AppBundle\Entity\Image;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

    
class SeasonCommand extends BaseCommand
{

    protected $em;
    protected $serviceTrackt;
    const MAX_SEASONS = 50;
    
    protected function configure()
    {
        $this
           ->setName('sync:season')
           ->setDescription('Enregistre en base les saison présentes sur trakt');
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
        $series = $this->em->getRepository('AppBundle:Serie')->findAll();       
        $nbFound = count($series);
        $output->writeln("<info> ".$nbFound." series trouvées</info>\n\n\n\n");
        $bar = $this->getProgressBar($nbFound, 'Mise à jour en cours');
        $counter = 0;
        
        foreach ($series as $serie) {
            $seasons = $this->serviceTrackt->getSeasonsBySerie($serie->getIdTrakt(),'full,images');
            foreach($seasons as $season){
                if($this->checkSeason($serie,$season)){
                    if(++$counter > self::MAX_SEASONS)
                        break 2;
                }
            }
            
            $bar->advance();        
        }
        $this->em->flush();
        $bar->setMessage('Terminé', 'title');
        if($counter <= self::MAX_SEASONS)
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
    private function checkSeason($serie, $info)
    {
        $season = $this->em->getRepository('AppBundle:Season')->findByIdTrakt($info['ids']['trakt']);
       
        if(!$season){
            $season = $this->createSeason($info);
            $serie->addSeason($season);
            $this->em->persist($serie);
            $this->em->persist($season);
            
            return true;
        }
        return false;
    }    
        
    private function createSeason($info)
    {
        $season = new Season();
        $season->setIdTrakt($info['ids']['trakt'])
              ->setIdTvdb($info['ids']['tvdb'])
              ->setSummary($info['overview'])
              ->setNumber($info['number'])
              ->setEpisodeCount($info['episode_count'])
              ->setAiredEpisodes($info['aired_episodes'])
              ->setRating($info['rating']); 
        
  //      $this->insertImage($season,$info['images']);  
        
        return $season;
    }
}    
