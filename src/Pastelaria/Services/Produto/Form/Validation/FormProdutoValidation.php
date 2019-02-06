<?php

namespace Pastelaria\Services\Produto\Form\Validation;

use Respect\Validation\Validator as v;
use Pastelaria\Helper\NotificationErrorRespectValidationAdpter;

class FormProdutoValidation extends NotificationErrorRespectValidationAdpter
{
    protected function getErrorsMessages($data)
    {
        return [
            'nome'       => ['O campo quantidade não pode ser vazio!',[]],
            'descricao'  => ['O campo produtos não pode ser vazio!',[]],
            'valor'      => ['O campo valor não pode ser vazio!',[]],
            'tipo'       => ['O campo tipo não pode ser vazio!',[]],

        ];
    }

    public function validate($data)
    {
        return parent::validate($data);
    }

    protected function getValidation($data)
    {

        v::with('Pastelaria\\Validation\\Rules\\', true);

        return v::arrayType()
            ->key('nome' , v::stringType()->notEmpty()->setName("nome"))
            ->key('descricao' , v::stringType()->notEmpty()->setName("descricao"))
            ->key('valor' , v::stringType()->notEmpty()->setName("valor"))
            ->key('tipo' , v::stringType()->notEmpty()->setName("tipo"));

    }
}