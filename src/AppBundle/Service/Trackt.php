<?php

namespace AppBundle\Service;
    
use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;

/**
* Classe to use BetaSeries API
*/
class Trackt extends GenericService
{
    const LIMIT = 10; // result par page for paginated result
    
    //TODO récupérer le token en base
    function __construct($server, $client_id, $client_secret, $token)
    {
        $this->clientId = $client_id;
        $this->clientSecret = $client_secret;
        $this->token = $token;
        $client = new Client( array('base_url' => $server, 'defaults'=>array('exceptions' => false) ) );
        $options = array('headers' => array('Content-Type' => 'application/json',
                                                  'Authorization' => "Bearer $token",
                                                  'trakt-api-key' => "$client_id",
                                                  'trakt-api-version' => '2'));
        parent::__construct($client,$options);   
    }
    // GLOBAL
    
    /**
     * Get a list of all genres, including names and slugs.
     *
     * @param string $type (shows/movies) 
     * @return array of nale and slug
     * @author Niko
     */
    public function getGenres($type='shows')
    {
        return $this->get('/genres/'.$type);
    }
    
    // SEARCH
    /**
     * undocumented function
     *
     * @param string $query 
     * @param string $type (movie, show, episode, person, list) 
     * @param string $year 
     * @return void
     * @author Niko
     */
    public function search($query,$type='show',$year='',$page=1)
    {
        return $this->get("search?query=$query&type=$type&year=$year&page=$page&limit=".self::LIMIT);
    }
    
    // COLLECTION
    
    public function getCollection($type='shows')
    {
        return $this->get('sync/collection/'.$type);
    }
    
    // CALENDAR
    /**
     * Returns all new show premieres (season 1, episode 1) airing during the time period specified.
     *
     * @param string $from Start the calendar on this date (YYYY-MM-DD)
     * @param int $days Number of days to display
     * @return array of show and episode
     * @author Niko
     */
    public function getAllNewShows($from, $days)
    {
        return $this->get("calendars/all/shows/new/$from/$days");
    }

    /**
     * Returns my show episodes airing during the time period specified.
     *
     * @param string $from Start the calendar on this date (YYYY-MM-DD)
     * @param int $days Number of days to display
     * @return array of show and episode
     * @author Niko
     */
    public function getMyAiredEpisode($from, $days)
    {
        return $this->get("calendars/my/shows/$from/$days");
    }
    
    /**
     * Returns my show premieres (any season, episode 1) airing during the time period specified.
     *
     * @param string $from Start the calendar on this date (YYYY-MM-DD)
     * @param int $days Number of days to display
     * @return array of show and episode
     * @author Niko
     */
    public function getMySeasonPremiere($from, $days)
    {
        return $this->get("calendars/my/shows/premieres/$from/$days");
    }
    
    /**
     * Add shows(/seasons(/episodes)) or episodes(ids) to collected item 
     *
     * @param string $shows 
     * @param string $episodes 
     * @return void
     * @author Niko
     */
    public function addCollected($shows, $episodes)    
    {
        $options['body'] = json_encode(
                                array("shows"    => $shows,
                                      "episodes" => $episodes
                                )
                           );
                           
        return $this->post('sync/collection',$options);
    }
    
    public function getWatched()
    {
        return $this->get('sync/watched/shows');
    }
    
    public function getHistory($type, $id)
    {
        return $this->get("sync/history/$type/$id");
    }
    
    public function addToHistory($shows, $episodes)   
    {
        $options['body'] = json_encode(
                                array("shows"    => $shows,
                                      "episodes" => $episodes
                                )
                            );
                              
        return $this->post('sync/history',$options);
    } 
    
    public function removeFromHistory($shows, $episodes, $ids)   
    {
        $options['body'] = json_encode(
                               array("shows"    => $shows,
                                     "episodes" => $episodes,
                                      "ids"     => $ids
                               )
                            );
                              
        return $this->post('sync/history/remove',$options);
    }
    
    public function getWatchlist($type="shows")
    {
        return $this->get('sync/watchlist/$type');
    }
    
    public function addToWatchlist($value='')
    {
        $options['body'] = json_encode(
                                array("shows"    => $shows,
                                      "episodes" => $episodes
                                )
                            );
                              
        return $this->post('sync/watchlist',$options);
    }
    
    public function removeFromWatchlist($value='')
    {
        $options['body'] = json_encode(
                                array("shows"    => $shows,
                                      "episodes" => $episodes
                                )
                            );
                              
        return $this->post('sync/watchlist/remove',$options);
    }
    
    private function getTranslation($idSerie, $language)
    {
        return $this->get("shows/$idSerie/translations/$language");
    }
    
    private function getInformations($idSerie,$level)
    {
        return $this->get("/shows/$idSerie?extended=$level");
    }
    
    public function getTranslatedInfos($id, $language='fr')
    {
        $info = $this->getInformations($id,'full,images');
        $translation = $this->getTranslation($id,$language);

        if(!empty($translation[0]['overview']))
            $info['overview'] = $translation[0]['overview'];
        
        return $info;
    }
    
    public function getUpdated()
    {
        $update = $this->get("/shows/updates/".date('Y-m-d',strtotime("-1 days")));
        
        return $this->orderAndUnique($update);
    }
    
    public function getMySeries()
    {
       return $this->orderAndUnique(array_merge($this->getWatched(),$this->getCollection()));
    }
    
    public function getFullSeason($idSerie)
    {
       return $this->get("/shows/$idSerie/seasons?extended=full,images,episodes");
    }

    public function getSeasonsBySerie($idSerie,$level='')
    {
       return $this->get("/shows/$idSerie/seasons?extended=$level");
    }
    
    public function getSeason($idSerie,$number, $level)
    {
       return $this->get("/shows/$idSerie/seasons/$number?extended=$level");
    }
    
    private function orderAndUnique($series)
    {
        foreach($series as $serie)
            $res[$serie['show']['ids']['trakt']] = $serie;
        
        return $res;
    }
    
    public function getSeriesPeople($id,$level)
    {
        return $this->get("/shows/$id/people?extended=$level");
    }
    
    public function getEpisodes($idSerie,$seasonNumber,$level)
    {
        return $this->get("/shows/$idSerie/seasons/$seasonNumber?extended=$level");
    }  
} 