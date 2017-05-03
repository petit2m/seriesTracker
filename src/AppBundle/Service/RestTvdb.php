<?php
namespace AppBundle\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;

/**
* Service class for new tvdb rest service
*/
class RestTvdb extends GenericService
{
    CONST IMAGE_URL = 'thetvdb.com/banners/_cache/'; 
        
    function __construct($server, $apiKey, $userName, $userKey, $language='en')
    {
        $this->language = $language;
        $this->apiKey   = $apiKey;
        $this->userName = $userName;
        $this->userKey = $userKey;
        $this->language = $language;
        $this->client   = new Client( array('base_url' => $server, 'defaults'=>array('exceptions' => false) ) );
        $this->options  = array('headers' => array('Content-Type' => 'application/json',
                                                   'Accept-Language' => $language,
                                                   'Accept' => 'application/vnd.thetvdb.v2.1.2'));
        $this->login();                                    
    }
    
    public function login()
    {
        $options['body'] = json_encode(
                                array("apikey"   => $this->apiKey,
                                      "username" => $this->userName,
                                      "userkey" => $this->userKey
                                )
                           );
                                               
        $res = $this->post('/login', $options);    

        $token = $res['token'];        
        $this->options['headers']['Authorization'] = "Bearer ".$token; 

        return true;                        
    }
    
    ////////////////////////
    // EPISODES FUNCTIONS //
    ////////////////////////
    
    /**
     * Retourne un episode
     *
     * @param string $id 
     * @return void
     * @author Nicolas
     */
    public function getEpisodeById($id)  
    {   
       return $this->get('/episodes/'.$id);
    }   
    
    /**
     * Retourne les épîsodes d'une série
     *
     * @param int $id : identifiant de la serie
     * @param int $page : identifiant de pagination ( 100 épsiodes par page)
     * @return array  : tableau d'episodes
     * @author Niko
     */
    public function getEpisodesBySerie($serieid, $page = 1)
    {
        return $this->get('/series/'.$serieid.'/episodes?page='.$page);
    }
    
    /**
     * Cherche un épisode
     *
     * @param string $serieid 
     * @param string $page 
     * @param string $absoluteNumber 
     * @param string $airedSeason 
     * @param string $airedEpisode 
     * @param string $imdbId 
     * @return void
     * @author Nicolas
     */
    public function searchEpisodes($serieid, $page = 1,  $absoluteNumber = false, $airedSeason = false, $airedEpisode = false, $imdbId = false)
    {
        $query = '/series/'.$serieid.'/episodes/query?page='.$page;
            
        if($absoluteNumber) $query .= '&absoluteNumber='.$absoluteNumber;
        if($airedSeason)    $query .= '&airedSeason='.$airedSeason;
        if($airedEpisode)   $query .= '&airedEpisode='.$airedEpisode;
        if($imdbId)         $query .= '&$imdbId='.$imdbId;
        
        return $this->get($query);
    }
  
    /**
     * undocumented function
     *
     * @param string $serieId 
     * @return void
     * @author Nicolas
     */
    public function getSearchEpisodeParams($serieId)
    {
        return $this->get('/series/'.$serieId.'/episodes/query/params');
    }
    
    
    //////////////////////
    // SERIES FUNCTIONS //
    //////////////////////
    
    /**
     * undocumented function
     *
     * @param string $name 
     * @param string $imdbId 
     * @param string $zap2itId 
     * @return void
     * @author Nicolas
     */
    public function searchSerie($name = '', $imdbId = 0, $zap2itId = 0)
    {
        $query = '/search/series?';
            
        if($name)       $query .= '&name='.$name;
        if($imdbId)     $query .= '&imdbId='.$imdbId;
        if($zap2itId)   $query .= '&zap2itId='.$zap2itId;
        
        return $this->get($query);
    }
    
    /**
     * undocumented function
     *
     * @param string $serieId 
     * @return void
     * @author Nicolas
     */
    public function getSerieSearchParam($serieId)
    {
        return $this->get('/search/series/params');
    }      
    
    public function getSerieById($id)  
    {
        return $this->get('/series/'.$id);
    } 
    
    public function getSerieLastUpdatedTime($id)  
    {
        return $this->head('/series/'.$id, 'Last-Modified');
    } 
    
    public function getSerieSummary($serieId)
    {
        return $this->get('/series/'.$serieId.'/episodes/summary');
    }
  
    public function getAvailableSerieInfos($serieId)
    {
        return $this->get('/series/'.$serieId.'/filter/params');
    }   

    public function getSerieInfos($serieId, $params=array())
    {
        if(empty($params))
            return false;
        
        $query=implode(',', $params);
        
        return $this->get('/series/'.$serieId.'/filter?keys='.$query);
    } 
    
    public function updatedSeries($fromTime, $toTime = '')
    {
       $query = '/updated/query?fromTime='.$fromTime;
       
       if($toTime) $query .= '&toTime='.$toTime;
       
       return $this->get($query); 
    }
    
    public function getUpdatedSeriesParams($serieId)
    {
        return $this->get('/updated/query/params');
    }   
      
    //////////////////////
    // IMAGES FUNCTIONS //
    //////////////////////
    
    public function getSerieImagesSummary($serieId)
    {   
        return $this->get('/series/'.$serieId.'/images');
    }
    
    public function getSerieImages($serieId, $type = 'series', $subKey = null)
    {   
        $query = '/series/'.$serieId.'/images/query?keyType='.$type;
        
        // if($resolution)     $query .= '&resolution='.$resolution;
        if($subKey) $query .= '&subKey='.$subKey;
            
        return $this->get($query);
    }
    
    public function getSerieImageParams($serieId)
    {   
        return $this->get('/series/'.$serieId.'/images/query/params');
    }
    
    protected function getImagesUrl()
    {
        return static::IMAGE_URL;
    }
    
 public function getImages($serieId)
    {
        $tabImages = $this->getSerieImages($serieId);

        if(empty($tabImages)) {
            return array();
        }
        
        foreach($tabImages['data'] as $key => $image) {
                $note[$key] = $image['ratingsInfo']['average'];
        }
        $sortedImage = array_multisort($note, SORT_DESC,SORT_NUMERIC, $tabImages['data']);
            
        $return[] = array('format'=>'full',
                          'url' => $this->getImagesUrl().$tabImages['data'][0]['fileName'],
                          'type' => 'banner'
                      );                

        return $return;
    }
    
}
?> 