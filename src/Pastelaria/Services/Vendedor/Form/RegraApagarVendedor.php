<?php

namespace Pastelaria\Services\Vendedor\Form;

use Pastelaria\Entity\Vendedor;
use Pastelaria\Services\Vendedor\Form\Storage\FormVendedorStorage;
use Pastelaria\Helper\NotificationError;

class RegraApagarVendedor
{
    protected $storage;
    protected $notificationError;

    public function __construct(FormVendedorStorage $storage , NotificationError $notificationError)
    {
        $this->storage = $storage;
        $this->notificationError = $notificationError;
    }

    public function apagar(Vendedor $vendedor = null)
    {
        $vendedorInfo = null;


        $this->storage->remove($vendedor);

        return $vendedorInfo;
    }

}