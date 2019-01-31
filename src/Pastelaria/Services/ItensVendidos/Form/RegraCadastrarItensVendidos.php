<?php

namespace Pastelaria\Services\ItensVendidos\Form;

use Pastelaria\Entity\ItensVendidos;
use Pastelaria\Services\ItensVendidos\Form\Validation\ItensVendidosValidation;
use Pastelaria\Services\ItensVendidos\Form\ParserItensVendidos;
use Pastelaria\Services\ItensVendidos\Form\Storage\FormItensVendidosStorage;

class RegraCadastrarItensVendidos
{

    protected $itensVendidosValidation;
    protected $parser;
    protected $storage;

    public function setItensVendidosValidation(ItensVendidosValidation $validation)
    {
        $this->itensVendidosValidation = $validation;
    }

    public function setParserItensVendidos(ParserItensVendidos $parse)
    {
        $this->parser = $parse;
    }

    public function setFormItensVendidosStorage(FormItensVendidosStorage $storage)
    {
        $this->storage = $storage;
    }

    public function cadastrar( $data , ItensVendidos $itens_vendidos)
    {
        $dataIsValid = $this->ItensVendidosValidation->validate($data);

        if($dataIsValid){
            $this->parser->setItensVendidosFromData($data, $itens_vendidos);
            $this->storage->save($itens_vendidos);
            $this->indexar(
                $itens_vendidos->getVenda()->getId(), $itens_vendidos->getId(), $itens_vendidos->toArray());
        }

        return $itens_vendidos->toArray();
    }
}