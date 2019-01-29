<?php

namespace Pastelaria\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Venda
 * @ORM\Entity(repositoryClass="FullarmAdm\Repository\OperadoraRepository")
 * @ORM\Table(name="venda")
 */
class Venda
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
    private $total;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $data;

    /**
     * @var string
     * @ORM\Column(type="string", length=280)
     */
    private $observacoes;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $status;

    /**
     * @var Vendedor
     * @ORM\ManyToOne(targetEntity="Vendedor")
     * @ORM\JoinColumn(name="id_vendedor",referencedColumnName="id", onDelete="CASCADE")
     */
    protected $vendedor;



    public function getId() {
        return $this->id;
    }
    public function getTotal() {
        return $this->total;
    }

    public function getData(){
        return $this->data;
    }

    public function getObservacoes(){
        return $this->observacoes;
    }

    public function getStatus(){
        return $this->status;
    }

    public function getVendedor(){
        return $this->vendedor;
    }



    public function setId($id) {
        $this->id = $id;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function setObservacoes($observacoes) {
        $this->observacoes = $observacoes;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setVendedor($vendedor) {
        $this->vendedor = $vendedor;
    }



    public function toArray()
    {

        return [
            'id'          => $this->id,
            'total'       => $this->total,
            'data'        => $this->data,
            'observacoes' => $this->observacoes,
            'status'      => $this->status
        ];
    }
}
