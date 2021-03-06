<?php

namespace Pastelaria\Entity;

use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\Mapping as ORM;
/**
 * Vendedor
 * @ORM\Entity(repositoryClass="Pastelaria\Repository\")
 * @ORM\Table(name="vendedor")
 */
class Vendedor
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
     * @ORM\Column(type="string", length=50)
     */
    private $nome;


    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $cpf;


    public function getId() {
        return $this->id;
    }
    public function getNome() {
        return $this->nome;
    }

    public function getCpf(){
        return $this->cpf;
    }



    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }



    public function toArray()
    {
        return [
            'id'   => $this->id,
            'nome' => $this->nome,
            'cpf'  => $this->cpf
        ];
    }
}
