<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Serie;

class SerieController extends Controller
{
    
    public function imageAction(Serie $serie, $type, $format)
    {
        $image = $serie->getImagesByTypeAndFormat($type, $format);
        
        if($image){
            $response = $this->render('AppBundle:Serie:image.html.twig',array('image' => $image[0]));
       //     $response->setPublic();
    //        $response->setSharedMaxAge(600);
            
            return $response;        
        }  
    }
    
    public function viewAction($slug)
    {
        $em = $this->getDoctrine()->getManager();      
        $serie = $em->getRepository('AppBundle:Serie')->findBySlug($slug);
      
        $seasons = $em->getRepository('AppBundle:Season')->findBySerie($serie);

        if($serie)
            return $this->render('AppBundle:Serie:serie.html.twig', array('serie' => $serie[0],'seasons' => $seasons));

        return new Response('Serie not found',404);
    }
}
