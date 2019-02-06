<?php

namespace Pastelaria\Services\Produto\Form\Validation;

use Pastelaria\Helper\NotificationError;
use Pastelaria\Services\Produto\Form\Validation\FormProdutoValidation;
use Symfony\Component\HttpFoundation\Response;

class ProdutoValidation
{

    protected $formValidation;
    protected $notificationError;

    public function setFormNotificationPanel(NotificationError $fv)
    {
        $this->notificationError = $fv;
    }

    public function setFormValidation(FormProdutoValidation $formValidation)
    {
        $this->formValidation = $formValidation;
    }

    public function validate($data)
    {
        $formIsValid = $this->formValidation->validate($data);

        if(!$formIsValid){
            $this->notificationError->setCodigoErro(Response::HTTP_BAD_REQUEST);
        }

        return ($formIsValid);

    }

}