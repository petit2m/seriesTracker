<?php
namespace AppBundle\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;

/**
* Service class for Fanart service
*/
class Fanart extends GenericService
{
    const SERVICE_VERSION = 'v3';
    
    const IMAGES_TYPE = array(
        'hdtvlogo' => 'logo',
        'tvbanner'   => 'banner',
        'hdclearart'    => 'clearart'
    );
                
    function __construct($server, $clientKey, $apiKey)
    {        
        $this->client   = new Client( array('base_url' => $server, 'defaults'=>array('exceptions' => false) ) );
        $this->options  = array('headers' => array('Content-Type' => 'application/json',
                                                    'api-key' => $apiKey,
                                                    'client-key' => $clientKey));
    }
      
    //////////////////////
    // IMAGES FUNCTIONS //
    //////////////////////
        
    public function getSerieImages($serieId)
    {   
        return $this->get('tv/'.$serieId);
    }
    
    protected function get($query)
    {
        return parent::get(static::SERVICE_VERSION.'/'.$query);
    }
    
    protected function getImagesUrl()
    {  
        
        return $this->conf['images']['secure_base_url'];   
    }
    
    public function getImages($serieId)
    {
      
        $tabImages = $this->getSerieImages($serieId);

        if(empty($tabImages)) {
            return array();
        }
        
        foreach($tabImages as $type => $images) {
            if( array_key_exists($type,static::IMAGES_TYPE)) {
                foreach($images as $key => $image) {
                    if(in_array($image['lang'],array('en','fr'))) {
                        $note[$key] = $image['likes'];
                        $res[$key] = $image;
                    }
                }
                if(!empty($res)) {
                    $sortedImage = array_multisort($note, SORT_DESC,SORT_NUMERIC, $res);
                }else{
                    return array();
                }
                $return[] = array('format'=>'full',
                                  'url' => $images[0]['url'],
                                  'type' => static::IMAGES_TYPE[$type]
                            );                
                unset($res);
                unset($note);
            }
        }
        
        return $return;
    }
}
?> 