<?php

namespace Pastelaria\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * ItensVendidos
 * @ORM\Entity(repositoryClass="Pastelaria\Repository\")
 * @ORM\Table(name="itens_vendidos")
 */
class ItensVendidos
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $quantidade;


    /**
     * @var Produto
     * @ORM\ManyToOne(targetEntity="produto")
     * @ORM\JoinColumn(name="id_produto",referencedColumnName="id", onDelete="CASCADE")
     */
    protected $produto;

    /**
     * @var Venda
     * @ORM\ManyToOne(targetEntity="venda")
     * @ORM\JoinColumn(name="id_venda",referencedColumnName="id", onDelete="CASCADE")
     */
    protected $venda;



    public function getId() {
        return $this->id;
    }
    public function getQuantidade() {
        return $this->quantidade;
    }

    public function getProduto(){
        return $this->produto;
    }

    public function getVenda(){
        return $this->venda;
    }



    public function setId($id) {
        $this->id = $id;
    }

    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }

    public function setProduto($produto) {
        $this->produto = $produto;
    }

    public function setVenda($venda) {
        $this->venda = $venda;
    }



    public function toArray()
    {

        return [
            'id'         => $this->id,
            'quantidade' => $this->quantidade
        ];
    }
}
