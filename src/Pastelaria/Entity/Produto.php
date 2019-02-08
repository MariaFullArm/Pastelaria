<?php

namespace Pastelaria\Entity;

use Doctrine\DBAL\Types\DecimalType;
use Doctrine\ORM\Mapping as ORM;
/**
 * Produto
 * @ORM\Entity(repositoryClass="Produto\Repository\")
 * @ORM\Table(name="produto")
 */
class Produto
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
     * @ORM\Column(type="string", length=60)
     */
    private $nome;

    /**
     * @var string
     * @ORM\Column(type="string", length=150)
     */
    private $descricao;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $valor;

    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     */
    private $tipo;


    public function getId() {
        return $this->id;
    }
    public function getDescricao() {
        return $this->descricao;
    }

    public function getNome(){
        return $this->nome;
    }

    public function getValor() {
        return $this->valor;
    }

    public function getTipo(){
        return $this->tipo;
    }



    public function setId($id) {
        $this->id = $id;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }



    public function toArray()
    {

        return [
            'id'         => $this->id,
            'descricao'  => $this->descricao,
            'nome'       => $this->nome,
            'valor'      => $this->valor,
            'tipo'       => $this->tipo
        ];
    }
}
