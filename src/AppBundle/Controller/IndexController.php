<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Serie;

class IndexController extends Controller
{
  
  // image controller ?
  public function imageAction($entite, $type, $format)
  {
      $image = $entite->getImagesByTypeAndFormat($type, $format);
      
      if($image){
          $response = $this->render('AppBundle:Serie:image.html.twig',array('image' => $image[0]));
          $response->setPublic();
          $response->setSharedMaxAge(600);
          
          return $response;        
      }  
  }
  
    public function indexAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();      
        $series = $em->getRepository('AppBundle:Serie')->findAll();
       
        return $this->render('AppBundle::index.html.twig', array('series' => $series));
    }
    
}
