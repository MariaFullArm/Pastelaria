<?php

namespace Pastelaria\Services\ItensVendidos\Form\Validation;

use Pastelaria\Helper\NotificationError;
use Pastelaria\Services\ItensVendidos\Form\Validation\FormItensVendidosValidation;
use Symfony\Component\HttpFoundation\Response;


class ItensVendidosValidation
{
    protected $formValidation;
    protected $notificationError;

    public function setFormNotificationPanel(NotificationError $fv)
    {
        $this->notificationError = $fv;
    }

    public function setFormValidation(FormItensVendidosValidation $formValidation)
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