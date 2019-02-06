<?php

namespace Pastelaria\Services\ItensVendidos\Form;

use Pastelaria\Entity\ItensVendidos;
use Pastelaria\Services\ItensVendidos\Form\Storage\FormItensVendidosStorage;
use Pastelaria\Helper\NotificationError;

class RegraApagarItensVendidos
{
    protected $storage;
    protected $notificationError;

    public function __construct(FormItensVendidosStorage $storage , NotificationError $notificationError)
    {
        $this->storage = $storage;
        $this->notificationError = $notificationError;
    }

    public function apagar(ItensVendidos $itens_vendidos = null)
    {
        $itensVendidosInfo = null;

        $this->storage->remove($itens_vendidos);

        return $itensVendidosInfo;
    }
}