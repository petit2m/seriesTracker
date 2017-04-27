<?php
    
namespace AppBundle\Entity;

use Doctrine\Common\Collections\Criteria;

class IllustratedItem
{  
    public function getImagesByTypeAndFormat($type, $format) {
        $newerCriteria = Criteria::create()
            ->where(Criteria::expr()->eq('type', $type))
                ->andWhere(Criteria::expr()->eq('format', $format));               

        return $this->getImages()->matching($newerCriteria);
    }
    
    public function getClass()
    {
        return get_class($this);
    }
}