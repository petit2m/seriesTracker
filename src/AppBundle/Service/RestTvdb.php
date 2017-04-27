<?php
namespace AppBundle\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;

/**
* Service class for new tvdb rest service
*/
class RestTvdb
{
    
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
    

    public function searchEpisodes($serieid, $page = 1,  $absoluteNumber = false, $airedSeason = false, $airedEpisode = false, $imdbId = false)
    {
        $query = '/series/'.$serieid.'/episodes/query?page='.$page;
            
        if($absoluteNumber) $query .= '&absoluteNumber='.$absoluteNumber;
        if($airedSeason)    $query .= '&airedSeason='.$airedSeason;
        if($airedEpisode)   $query .= '&airedEpisode='.$airedEpisode;
        if($imdbId)         $query .= '&$imdbId='.$imdbId;
        
        return $this->get($query);
    }
  
    public function getSearchEpisodeParams($serieId)
    {
        return $this->get('/series/'.$serieId.'/episodes/query/params');
    }
    
    
    //////////////////////
    // SERIES FUNCTIONS //
    //////////////////////
    
    public function searchSerie($name = '', $imdbId = 0, $zap2itId = 0)
    {
        $query = '/search/series?';
            
        if($name)       $query .= '&name='.$name;
        if($imdbId)     $query .= '&imdbId='.$imdbId;
        if($zap2itId)   $query .= '&zap2itId='.$zap2itId;
        
        return $this->get($query);
    }
    
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
    
    public function getSerieImages($serieId, $type = 'banner', $resolution = '', $params = array())
    {   
        $query = '/series/'.$serieId.'/images/query?keyType='.$type;
        
        if($resolution)     $query .= '&resolution='.$resolution;
        if(!empty($params)) $query .= '&subKey='.implode(',',$params);
            
        return $this->get($query);
    }
    
    public function getSerieImageParams($serieId)
    {   
        return $this->get('/series/'.$serieId.'/images/query/params');
    }
    
    private function post($uri, $options)
    {
        $response = $this->client->post($uri, array_merge($this->options,$options));   
        $res = $this->checkResponse($response);      
        
        return $res;
    }
    
    private function get($uri)
    {
        $response = $this->client->get($uri, $this->options);
        $res = $this->checkResponse($response);      
                
        return $res;
    }
    
    private function head($uri, $param = false)
    {
        $response = $this->client->head($uri, $this->options);

        if($response->getStatusCode() != 200){
            $res = $response->getHeaders();
            if(isset($res['Error']))  
                $this->log($response->getStatusCode(),$res['Error']);
            
            return false;
        }
        
        if($param)
            return $response->getHeader($param);
        else
            return $response->getHeaders();
    }
        
    private function checkResponse(Response $response)
    {
        if($response->getStatusCode() != 200){
            $res = $response->json();
            if(isset($res['Error']))  
                $this->log($response->getStatusCode(),$res['Error']);
            
            return false;
        }
        
        return $response->json();
    }
    
    //TODO : integrer un vrai logger ? monolog ? pour stocker les données en bdd
    private function log($error_id, $message)
    {  
        throw new \Exception($message); //on lève une exception en attendant mieux... pas très utile de désactiver les exceptions pour en relever derrière néanmoins
    }
    
}
?> 