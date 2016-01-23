<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Season;

class SeasonController extends Controller
{
    
    public function viewAction($slug,$number)
    {
        $em = $this->getDoctrine()->getManager();      
        $season = $em->getRepository('AppBundle:Season')->findBySlugAndNumber($slug,$number);

        if($season)
            return $this->render('AppBundle:Season:season.html.twig', array('season' => $season[0]));

        return new Response('Season not found',404);
    }
}
