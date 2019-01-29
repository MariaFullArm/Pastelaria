<?php

namespace Pastelaria\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 * @ORM\Entity(repositoryClass="Pastelaria\Repository\ConfigRepository")
 * @ORM\Table(name="confg")
 */

class Config
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
    * @var string
    * @ORM\Column(type="string", nullable=true)
    */
    protected $token;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getToken()
    {
        return $this->token;
    }
    
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }
}
