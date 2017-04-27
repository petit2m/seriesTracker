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
         $series = $serviceBS->getSerieImages(62852);
         $series = $serviceBS->getConfiguration();
         var_dump($series);die;

//        $serviceBS = $this->get('serviceRestTvdb');
    //    $series = $serviceBS->getSerieImageParams(279536);
  //      $series = $serviceBS->getSerieImagesSummary(279536);
//        $series = $serviceBS->getSerieImages(279536,'series');
//        var_dump($series);die;

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
