<?php

namespace Pastelaria\Services;

use Pastelaria\Services\AuthenticationInterface;


class AuthenticationServiceApi implements AuthenticationInterface
{
    
    private $em;
    private $token;
    
    public function authenticate($token)
    {
        if (is_null($this->getEntityManager())) {
            throw new \RuntimeException('Entity Manager is null, please set it');
        }
        
        if (is_null($token) || (strlen($token) < 20)) {
            return false;
        }
        
        $configRepository = $this->getEntityManager()->getRepository('Pastelaria\Entity\Config');
        $this->token = $configRepository->findOneBy(['token' => $token]);
        
        if (is_null($this->token)) {
            return false;
        }
        
        return true;
    }
    
    public function setEntityManager($em)
    {
        $this->em = $em;
    }
    
    public function getEntityManager()
    {
        return $this->em;
    }
    
    public function getToken()
    {
        return $this->token;
    }
}
