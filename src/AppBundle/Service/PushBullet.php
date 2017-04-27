<?php

namespace AppBundle\Service;
    
use GuzzleHttp\Client;

/**
* Classe to use BetaSeries API
*/
class PushBullet extends GenericService
{
    //TODO récupérer les token en base
    function __construct($server, $token)
    {
        $client = new Client( array('base_url' => $server, 'defaults'=>array('exceptions' => false) ) );
        $options = array('headers' => array('Content-Type' => 'application/json',
                                                  'Access-Token' => $token
                                              ));
        parent::__construct($client,$options);                                     
    }
    
    public function getPushes($active='true', $modifiedAfter= '1.4e+09')
    {
        return $this->get('pushes?active='.$active.'modified_after='.$modifiedAfter);
    }
    
    public function sendPush($value='')
    {
        $options['body'] = json_encode(
                                array('body'    => 'test',
                                      'type'    => 'note',
                                      'title'   => 'test'
                                )
                           );
                           
        return $this->post('pushes',$options);
    }
    
} 