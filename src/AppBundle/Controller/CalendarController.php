<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Serie;

class CalendarController extends Controller
{  
  
    private static function getEventType($id){
        $event = ['event-important', 'event-success', 'event-warning', 'event-info', 'event-inverse','event-special'];

        return isset($event[$id]) ? $event[$id] : 'event-important';
    }
    
    public function indexAction(Request $request, $route)
    {         
        return $this->render('AppBundle::calendar.html.twig', array('route' => $route));
    }
    
    public function newSeriesPremiereAction(Request $request)
    {   
        return $this->getCalendar($request,'getAllNewShows');
    }
    
    public function mySeasonsPremiereAction(Request $request)
    {
        return $this->getCalendar($request,'getMySeasonPremiere');
    }
    
    public function myEpisodeAction(Request $request)
    {
       return $this->getCalendar($request,'getMyAiredEpisode');
    }
    
    private function getCalendar($request,$function)
    {
        $result = array();
        $start=date('Y-m-d', $request->get('from')/1000);
        $end = date('Y-m-d', $request->get('to')/1000);
        $days = $request->get('to') - $request->get('from');
        $days = $days / (3600 * 1000 * 24);
        
        $serviceTrackt = $this->get('serviceTrackt');
        $events = $serviceTrackt->$function($start,$days);
        
        foreach($events as $id => $event) {
            $start = strtotime($event['first_aired']) * 1000;
            $result[]=array(
                "id"    => $event['show']['ids']['trakt'],
                "title" => $event['show']['title'].' S'.sprintf("%02d",$event['episode']['season']).'E'.sprintf("%02d",$event['episode']['number']),
                "url"   =>"series/preview/".$event['show']['ids']['slug'],
                "class" => $this->getEventType($id % 6),
                "start" => $start,
                "end"   => $start + 3600000
            );
        }    
        
        return new Response(json_encode(array(
             "success"=> 1,
             "result"=> $result
        )));
    }
    
}
/*
 array(3) {
    ["first_aired"]=>
    string(24) "2016-01-01T05:00:00.000Z"
    ["episode"]=>
    array(4) {
      ["season"]=>
      int(1)
      ["number"]=>
      int(1)
      ["title"]=>
      string(5) "Pilot"
      ["ids"]=>
      array(5) {
        ["trakt"]=>
        int(2063313)
        ["tvdb"]=>
        int(5411506)
        ["imdb"]=>
        NULL
        ["tmdb"]=>
        NULL
        ["tvrage"]=>
        NULL
      }
    }
    ["show"]=>
    array(3) {
      ["title"]=>
      string(16) "Follow The Money"
      ["year"]=>
      int(1969)
      ["ids"]=>
      array(6) {
        ["trakt"]=>
        int(103584)
        ["slug"]=>
        string(16) "follow-the-money"
        ["tvdb"]=>
        int(303439)
        ["imdb"]=>
        NULL
        ["tmdb"]=>
        NULL
        ["tvrage"]=>
        NULL
      }
    }
  }
*/
