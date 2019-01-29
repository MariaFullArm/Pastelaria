<?php

namespace Pastelaria\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * ApiAcessToken
 * @ORM\Entity(repositoryClass="Pastelaria\Repository\ApiAccessTokenRepository")
 * @ORM\Table(name="api_access_token",
 *  indexes={
 *      @ORM\Index(name="idx_access_token", columns={"access_token"})
 *  }
 * )
 */
class ApiAccessToken
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string", length=70, unique=true)
     */
    private $access_token;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $expira;


    
    public function getAccessToken()
    {
        return $this->access_token;
    }

    public function getExpira()
    {
        return $this->expira;
    }
    
    public function setAccessToken($token)
    {
        $this->access_token = $token;
        return $this;
    }
    
    public function setExpira(\DateTime $expira)
    {
        $this->expira = $expira;
        return $this;
    }
}
