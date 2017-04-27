<?php
namespace AppBundle\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;

/**
* Service class for new tvdb rest service
*/
class Tmdb extends GenericService
{
    const SERVICE_VERSION = 3;
        
    function __construct($server, $apiKey,$language='en')
    {
        $this->language = $language;
        $this->apiKey   = $apiKey;
        $this->language = $language;
        $this->client   = new Client( array('base_url' => $server, 'defaults'=>array('exceptions' => false) ) );
        $this->options  = array('headers' => array('Content-Type' => 'application/json',
                                                   'Accept-Language' => $language));

    }
      
    //////////////////////
    // IMAGES FUNCTIONS //
    //////////////////////
    
    public function getConfiguration()
    {
        return $this->get('configuration');
    }
        
    public function getSerieImages($serieId)
    {   
        return $this->get('tv/'.$serieId.'/images');
    }
    
    
    public function getSerieImageParams($serieId)
    {   
        return $this->get('/series/'.$serieId.'/images/query/params');
    }
    
    protected function get($query)
    {
        return parent::get(static::SERVICE_VERSION.'/'.$query.'?api_key='.$this->apiKey);
    }
    
    
    protected function getImagesUrl()
    {
        $conf = $this->getConfiguration();
        
        return $conf['secure_base_url'];
        
    }
}
?> 