<?php
    
namespace AppBundle\Entity;



interface IllustrableInterface 
{  
    public function getImagesByTypeAndFormat($type, $format);
    
    
}