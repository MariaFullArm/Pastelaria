<?php

namespace Pastelaria\Repository;

use Doctrine\ORM\EntityRepository;
use Pastelaria\Entity\ApiAccessToken;

class ApiAccessTokenRepository extends EntityRepository{
    
    public function getAccessToken() {
       
       $accesstoken = $this->findBy(array(), array('access_token' => 'DESC'),1);
       return ( empty($accesstoken) ? new ApiAccessToken() : $accesstoken[0]);
       
    }
    
}
