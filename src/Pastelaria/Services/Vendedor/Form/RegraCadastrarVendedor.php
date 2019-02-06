<?php

namespace Pastelaria\Services\Vendedor\Form;

use Pastelaria\Entity\Vendedor;
use Pastelaria\Services\Vendedor\Form\Validation\VendedorValidation;
use Pastelaria\Services\Vendedor\Form\ParserVendedor;
use Pastelaria\Services\Vendedor\Form\Storage\FormVendedorStorage;

class RegraCadastrarVendedor
{
    protected $vendedorValidation;
    protected $parser;
    protected $storage;

    public function setVendedorValidation(VendedorValidation $validation)
    {
        $this->vendedorValidation = $validation;
    }

    public function setParserVendedor(ParserVendedor $parse)
    {
        $this->parser = $parse;
    }

    public function setFormVendedorStorage(FormVendedorStorage $storage)
    {
        $this->storage = $storage;
    }

    public function cadastrar( $data , Vendedor $vendedor)
    {
        $dataIsValid = $this->VendedorValidation->validate($data);

        if($dataIsValid){
            $this->parser->setVendedorFromData($data, $vendedor);
            $this->storage->save($vendedor);
        }

        return $vendedor->toArray();
    }


}