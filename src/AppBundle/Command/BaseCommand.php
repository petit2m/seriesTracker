<?php

namespace AppBundle\Command;

use AppBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;    
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
* Base command class
* Add a design progress bar
*/
class BaseCommand extends ContainerAwareCommand
{
    private $output;
    
    function __construct($name = null)
    {
        parent::__construct('---');
    }
    
    protected function getProgressBar($nbIteration, $message)
    {
        $bar = new ProgressBar($this->output, $nbIteration);         
        ProgressBar::setPlaceholderFormatterDefinition('memory', function (ProgressBar $bar) {
                    static $i = 0;
                    $mem = memory_get_usage();
                    $colors = $i++ ? '41;37' : '44;37';
                    return "\033[".$colors.'m '.Helper::formatMemory($mem)." \033[0m";
                });
        $bar->setFormat(" \033[44;37m %title:-38s% \033[0m\n %current%/%max% %bar% %percent:3s%%\n 🏁  %remaining:-10s% %memory:37s%\n");
        $bar->setBarCharacter("\033[32m●\033[0m");
        $bar->setEmptyBarCharacter("\033[31m●\033[0m");
        $bar->setMessage($message, 'title');
        $bar->start();

        return $bar;
    }
    
    public function run(InputInterface $input, OutputInterface $output)
    {
       $this->output = $output;
       $result = parent::run($input, $output);
           
       return $result;
    }
    
    protected function persistAndSave($entity)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $em->persist($entity);
        $em->flush();
    }
    

}
