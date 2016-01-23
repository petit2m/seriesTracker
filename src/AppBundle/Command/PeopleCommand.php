<?php

namespace AppBundle\Command;

use AppBundle\Entity\Actor;
use AppBundle\Entity\Person;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

    
class PeopleCommand extends BaseCommand
{

    protected $em;
    protected $serviceTrackt;
    const MAX_CHARACTER = 1000;
    
    protected function configure()
    {
        $this
           ->setName('sync:people')
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
        $output->writeln("<comment> Démarrage de la mise à jour des personnages </comment>");
        $series = $this->em->getRepository('AppBundle:Serie')->findAll();   
        $nbFound = count($series);
        $output->writeln("<info> ".$nbFound." series trouvées</info>\n\n\n\n");
        $bar = $this->getProgressBar($nbFound, 'Mise à jour en cours');
        $counter = 0;
        foreach ($series as $serie) {
            $people = $this->serviceTrackt->getSeriesPeople($serie->getIdTrakt(),'full,images');
           
            foreach($people['cast'] as $person){
                if($this->checkCharacter($person,$serie)){
                    if(++$counter > self::MAX_CHARACTER)
                        break 2;
                }
            }
            
            $bar->advance();        
        }     
        
        $bar->setMessage('Terminé', 'title');
        if($counter <= self::MAX_CHARACTER)
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
    private function checkCharacter($person,$serie)
    {
        $character = $this->em->getRepository('AppBundle:Person')->findExisting($person['character'], $serie);
       
        if(!$character){
            $character = $this->createCharacter($person);
            $serie->addPerson($character);
            $this->persistAndSave($serie);
            
            return true;
        }
        
        return false;
    }    
        
    private function createCharacter($info)
    {
        $character = new Person();
        $character->setName($info['character']);
        
        $actor = $this->em->getRepository('AppBundle:Actor')->findByIdTrakt($info['person']['ids']['trakt']);
        if(!$actor){
            $actor = new Actor();
            $actor->setName($info['person']['name'])
                ->setIdTrakt($info['person']['ids']['trakt'])
                ->setSlug($info['person']['ids']['slug'])
                ->setBirthday(new \DateTime($info['person']['birthday']))
                ->setBirthplace($info['person']['birthplace'])
                ->setBiography($info['person']['biography']);


            if($info['person']['death'])
                 $actor->setDeath(new \DateTime($info['person']['death']));
            
            $this->insertImage($actor,$info['person']['images']);
            $this->em->persist($actor);
        }else{
            $actor = $actor[0];
        }
        
        $character->setActor($actor);
        $this->em->persist($character);
        
        return $character;
    }
}    
