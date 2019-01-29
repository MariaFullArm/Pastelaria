<?php

namespace Pastelaria\Repository;

use Doctrine\ORM\EntityRepository;
use Pastelaria\Entity\Config;

class ConfigRepository extends EntityRepository{
    
    public function getConfig() {
       
       $config = $this->findBy(array(), array('id' => 'DESC'),1);
       return ( empty($config) ? new Config() : $config[0]);
       
       
    }
    
}
