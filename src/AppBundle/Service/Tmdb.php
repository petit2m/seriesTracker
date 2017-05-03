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
    
    const IMAGES_TYPE = array(
        'backdrops' => 'fanart',
        'posters'   => 'poster',
        'stills'    => 'screenshot',
        'profiles'   => 'headshot'
    );
        
    const IMAGES_SIZE = array(
        'backdrops' => array('full'=>'original', 'medium'=>'w780','thumb'=>'w300'),
        'posters'   => array('full'=>'original', 'medium'=>'w500','thumb'=>'w300'),
        'stills'    => array('full'=>'original', 'medium'=>'w300','thumb'=>'w185'),
        'profiles'    => array('full'=>'original', 'medium'=>'w500','thumb'=>'w185'),
    );   
        
    function __construct($server, $apiKey,$language='en')
    {        
        $this->language = $language;
        $this->apiKey   = $apiKey;
        $this->language = $language;
        $this->client   = new Client( array('base_url' => $server, 'defaults'=>array('exceptions' => false) ) );
        $this->options  = array('headers' => array('Content-Type' => 'application/json',
                                                   'Accept-Language' => $language));
        
        $this->conf = $this->getConfiguration();
       // dump($this->conf);die;

    }
      
    //////////////////////
    // IMAGES FUNCTIONS //
    //////////////////////
    
    public function getConfiguration()
    {
        return $this->get('configuration');
    }
        
    public function getPersonImages($ersonId)
    {   
        return $this->get('person/'.$ersonId.'/images');
    }
            
    public function getSerieImages($serieId)
    {   
        return $this->get('tv/'.$serieId.'/images');
    }
    
    public function getSeasonImages($serieId, $seasonNumber)
    {   
        return $this->get('tv/'.$serieId.'/season/'.$seasonNumber.'/images');
    }
        
    public function getEpisodeImages($serieId, $seasonNumber, $episodeNumber)
    {   
        return $this->get('tv/'.$serieId.'/season/'.$seasonNumber.'/episode/'.$episodeNumber.'/images');
    }
     
    public function getSerieImageParams($serieId)
    {   
        return $this->get('/series/'.$serieId.'/images/query/params');
    }
    
    protected function get($query)
    {
        return parent::get(static::SERVICE_VERSION.'/'.$query.'?api_key='.$this->apiKey.'&language=en-US&append_to_response=images&include_image_language=en,null');
    }
    
    protected function getImagesUrl()
    {  
        
        return $this->conf['images']['secure_base_url'];   
    }
    
    public function getImages($mediaType, $itemId, $seasonNumber = null, $episodeNumber = null)
    {
        if (null != $seasonNumber && null != $episodeNumber) {
            $tabImages = $this->getEpisodeImages($serieId, $seasonNumber, $episodeNumber);
        } else if (null != $seasonNumber) {
            $tabImages = $this->getSeasonImages($serieId, $seasonNumber);
        } else {
            $tabImages = $this->getSerieImages($serieId);
        }
        
        return $this->sortImages($tabImages);
    }
    
    public function getActorImages($id)
    {
        return $this->sortImages($this->getPersonImages($id));
    }

    protected function sortImages($tabImages)
    {
        if(empty($tabImages)) {
            return array();
        }
        
        foreach($tabImages as $type => $images) {
            if( array_key_exists($type,static::IMAGES_TYPE)) {
                foreach($images as $key => $image) {
                    $size[$key] = $image['height'];
                    $note[$key] = $image['vote_average'];
                }
                if(!empty($size) && !empty($note)) {
                    $sortedImage = array_multisort($size, SORT_DESC,SORT_NUMERIC, $note, SORT_DESC,SORT_NUMERIC, $images);
                }else{
                    return array();
                }
                //boucle sur les formats
                foreach(static::IMAGES_SIZE[$type] as $format => $size) {
                    $return[] = array('format'=>$format,
                                  'url' => $this->getImagesUrl().$size.$images[0]['file_path'],
                                  'type' => static::IMAGES_TYPE[$type]
                              );                
                }
                unset($size);
                unset($note);
            }
        }
    
        return $return;
    }
}
?> 