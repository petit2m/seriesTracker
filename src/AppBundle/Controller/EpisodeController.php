<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class EpisodeController extends Controller
{
    
    public function viewAction($slug,$seasonNumber,$episodeNumber)
    {
        $em = $this->getDoctrine()->getManager();      
        $episode = $em->getRepository('AppBundle:Episode')->findBySlugAndNumbers($slug,$seasonNumber,$episodeNumber);

        if($episode)
            return $this->render('AppBundle:Episode:episode.html.twig', array('episode' => $episode[0]));

        return new Response('Episode not found',404);
    }
}
