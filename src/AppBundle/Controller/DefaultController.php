<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
  
    public function indexAction(Request $request)
    {
        // $serviceBS = $this->get('serviceTrackt');
      //   $series = $serviceBS->getCollection('shows');
      //   var_dump($series);die;
      //               //
         $serviceBS = $this->get('serviceTmdb');
         // $series = $serviceBS->getImages(1402);
            $series = $serviceBS->getActorImages(18916);
      // $series = $serviceBS->getConfiguration();
         dump($series);die;

//       $serviceBS = $this->get('serviceRestTvdb');
      // $series = $serviceBS->getSerieImageParams(273181);
  //          $series = $serviceBS->getImages(273181);
  //      $series = $serviceBS->getSerieImagesSummary(279536);
 //       $series = $serviceBS->getSerieImages(273181,'season');
  //      dump($series);die;      
        
     //    $serviceBS = $this->get('serviceFanart');
      // $series = $serviceBS->getSerieImageParams(273181);
       //     $series = $serviceBS->getPeopleImages(273181);
  //      $series = $serviceBS->getSerieImagesSummary(279536);
   //   $series = $serviceBS->getSerieImages(273181,'season');
        dump($series);die;

        // $serviceBS = $this->get('ServicePushBullet');
        // $push = $serviceBS->getPushes();
        // var_dump($push);die;
        
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
    
    public function menuAction()
    {
        return $this->render('AppBundle::menu.html.twig',array());
    }
}
