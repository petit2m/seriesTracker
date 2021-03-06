<?php
/**
* Standard Service Class
*/

namespace AppBundle\Service;
 
use GuzzleHttp\Message\Response;

class GenericService
{
    protected $client;
    protected $options;
    
    function __construct($client,$options) {
        $this->client = $client;
        $this->options = $options;
    }
    /**
     * POST function to server
     *
     * @param string $uri 
     * @param string $options 
     * @return result
     * @author Niko
     */
    protected function post($uri, $options)
    {
        $response = $this->client->post($uri, array_merge($this->options,$options));   
        $res = $this->checkResponse($response);      
        
        return $res;
    }
    
    /**
     * GET function
     *
     * @param string $uri 
     * @return $result
     * @author Niko
     */
    protected function get($uri)
    {
        $response = $this->client->get($uri, $this->options);
        $res = $this->checkResponse($response);      
                
        return $res;
    }
    
    /**
     * Check the return of a request response
     *
     * @param Response $response 
     * @return false if error, decoded response if valid status
     * @author Niko
     */
    private function checkResponse(Response $response)
    {
        if($response->getStatusCode() != 200){
            $res = $response->json();
            var_dump($response->getStatusCode());
            if(isset($res['Error']))  
                $this->log($response->getStatusCode(),$res['Error']);
            
            return false;
        }
        
        return $response->json();
    }
}