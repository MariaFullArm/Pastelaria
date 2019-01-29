<?php

namespace Pastelaria\Services;

use Pastelaria\Services\AuthenticationInterface;
use Pastelaria\Entity\LoginAccessToken;

class AuthenticationServiceApp implements AuthenticationInterface
{
    
    private $em;
    private $login;
    
    public function authenticate($token)
    {
        if (is_null($this->getEntityManager())) {
            throw new \RuntimeException('Entity Manager is null, please set it');
        }
        
        if (is_null($token) || (strlen($token) < 64)) {
            return false;
        }
        
        $loginAccessTokenRepository = $this->getEntityManager()->getRepository('Pastelaria\Entity\LoginAccessToken');
        $this->login = $loginAccessTokenRepository->findTokenByDateExpira($token, new \DateTime());
        
        if (is_null($this->login )) {
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
    
    public function getLoginAccessToken()
    {
        return $this->login;
    }
}
