<?php

namespace Pastelaria\Services\Produto\Form;

use Pastelaria\Entity\Produto;
use Pastelaria\Services\Produto\Form\Storage\FormProdutoStorage;
use Pastelaria\Helper\NotificationError;

class RegraApagarProduto
{
    protected $storage;
    protected $notificationError;

    public function __construct(FormProdutoStorage $storage , NotificationError $notificationError)
    {
        $this->storage = $storage;
        $this->notificationError = $notificationError;
    }

    public function apagar(Produto $produto = null)
    {
        $produtoInfo = null;


        $this->storage->remove($produto);

        return $produtoInfo;
    }
}