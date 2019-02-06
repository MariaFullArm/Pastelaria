<?php

namespace Pastelaria\Services\Produto\Form;

use Pastelaria\Entity\Produto;
use Pastelaria\Services\Produto\Form\Validation\ProdutoValidation;
use Pastelaria\Services\Produto\Form\ParserProduto;
use Pastelaria\Services\Produto\Form\Storage\FormProdutoStorage;

class RegraCadastrarProduto
{

    protected $produtoValidation;
    protected $parser;
    protected $storage;

    public function setProdutoValidation(ProdutoValidation $validation)
    {
        $this->produtoValidation = $validation;
    }

    public function setParserProduto(ParserProduto $parse)
    {
        $this->parser = $parse;
    }

    public function setFormProdutoStorage(FormProdutoStorage $storage)
    {
        $this->storage = $storage;
    }

    public function cadastrar( $data , Produto $produto)
    {
        $dataIsValid = $this->ProdutoValidation->validate($data);

        if($dataIsValid){
            $this->parser->setProdutoFromData($data, $produto);
            $this->storage->save($produto);
        }

        return $produto->toArray();
    }

}