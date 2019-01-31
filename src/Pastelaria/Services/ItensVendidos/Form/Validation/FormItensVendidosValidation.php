<?php
namespace Pastelaria\Services\ItensVendidos\Form\Validation;

use Respect\Validation\Validator as v;
use Pastelaria\Helper\NotificationErrorRespectValidationAdpter;

class FormItensVendidosValidation extends NotificationErrorRespectValidationAdpter
{
    protected function getErrorsMessages($data)
    {
        return [
            'quantidade'    => ['O campo quantidade não pode ser vazio!',[]],
            'produto'       => ['O campo produtos não pode ser vazio!',[]],

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
            ->key('quantidade' , v::stringType()->notEmpty()->setName("quantidade"))
            ->key('produto' , v::stringType()->notEmpty()->setName("produto"));

    }
}