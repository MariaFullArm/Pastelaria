<?php

namespace Pastelaria\Services\ItensVendidos\Form;

use Pastelaria\Entity\ItensVendidos;

class ParserItensVendidos
{
    public function setItensVendidosFromData($data , ItensVendidos $itens_vendidos)
    {
        $quantidade = $data['quantidade'];

        $itens_vendidos->setQuantidade($quantidade);

        $produto = $data['produto'];

        $itens_vendidos->setProduto($produto);

        return $itens_vendidos;
    }
}