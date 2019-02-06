<?php

namespace Pastelaria\Services\Vendedor\Form;

use Pastelaria\Entity\Vendedor;

class ParserVendedor
{
    public function setProdutoFromData($data , Vendedor $vendedor)
    {
        $nome = $data['nome'];

        $vendedor->setNome($nome);


        $cpf = $data['cpf'];

        $vendedor->setCpf($cpf);

    }

}