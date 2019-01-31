<?php

namespace Pastelaria\Services\Produto\Form;

use Pastelaria\Entity\Produto;

class ParserProduto
{
    public function setProdutoFromData($data , Produto $produto)
    {
        $nome = $data['nome'];

        $produto->setNome($nome);


        $descricao = $data['descricao'];

        $produto->setDescricao($descricao);


        $valor = $data['valor'];

        $produto->setValor($valor);


        $tipo = $data['tipo'];

        $produto->setTipo($tipo);

        return $produto;
    }
}