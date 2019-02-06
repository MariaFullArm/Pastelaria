<?php

namespace Pastelaria\Services\Vendedor\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Pastelaria\Helper\NotificationError;
use Pastelaria\Entity\Vendedor;

use Pastelaria\Services\Vendedor\Form\Storage\FormVendedorStorage;

use Pastelaria\Services\Vendedor\Form\Validation\VendedorValidation;
use Pastelaria\Services\Vendedor\Form\Validation\FormVendedorValidation;

use Pastelaria\Services\Vendedor\Form\ParserVendedor;

use Pastelaria\Services\Vendedor\Form\RegraCadastrarVendedor;
use Pastelaria\Services\Vendedor\Form\RegraBuscarVendedor;
use Pastelaria\Services\Vendedor\Form\RegraAtualizarVendedor;
use Pastelaria\Services\Vendedor\Form\RegraApagarVendedor;

class FormVendedorService
{

    protected $notificationError;
    protected $em;

    public function __construct(NotificationError $fne, EntityManager $em)
    {
        $this->notificationError = $fne;
        $this->em = $em;
    }

    public function cadastrar( $data )
    {
        $formVendedorStorage = new FormVendedorStorage($this->em);

        $formVendedorValidation = new FormVendedorValidation();
        $formVendedorValidation->setNotificationErrors($this->notificationError);

        $mensagemValidation = new VendedorValidation();
        $mensagemValidation->setFormNotificationPanel($this->notificationError);
        $mensagemValidation->setFormValidation($formVendedorValidation);

        $parseVendedor = new ParserVendedor();

        $formVendedor = new RegraCadastrarVendedor();
        $formVendedor->setVendedorValidation($mensagemValidation);
        $formVendedor->setParserVendedor($parseVendedor);
        $formVendedor->setFormVendedorStorage($formVendedorStorage);

        $vendedor = $this->getEntidadeVendedor($data);
        $vendedorInfo = $formVendedor->cadastrar($data, $vendedor);

        return $vendedorInfo;

    }

    public function apagar( $data )
    {
        $vendedor       = $this->getVendedor($data);
        $vendedorExiste  = !is_null($vendedor);
        $vendedorInfo    = null;

        if (!$vendedorExiste){
            $this->notificationError->addErro('vendedor', Response::$statusTexts[Response::HTTP_NOT_FOUND]);
            $this->notificationError->setCodigoErro(Response::HTTP_NOT_FOUND);
        } else {
            $formVendedorStorage = new FormVendedorStorage($this->em);


            $formVendedor = new RegraApagarVendedor($formVendedorStorage, $this->notificationError);

            $vendedorInfo = $formVendedor->apagar($vendedor);
        }

        return $vendedorInfo;

    }

    public function atualizar( $data )
    {
        $vendedor      = $this->getVendedor($data);
        $vendedorExiste = !is_null($vendedor);
        $vendedorInfo   = null;

        if ($vendedorExiste){

            $formVendedorStorage = new FormVendedorStorage($this->em);

            $formVendedorValidation = new FormVendedorValidation();
            $formVendedorValidation->setNotificationErrors($this->notificationError);

            $vendedorValidation = new VendedorValidation();
            $vendedorValidation->setFormNotificationPanel($this->notificationError);
            $vendedorValidation->setFormValidation($formVendedorValidation);

            $parseVendedor               = new ParserVendedor();

            $formVendedor = new RegraAtualizarVendedor();
            $formVendedor->setVendedorValidation($vendedorValidation);
            $formVendedor->setParserVendedor($parseVendedor);
            $formVendedor->setFormVendedorStorage($formVendedorStorage);


            $vendedorInfo = $formVendedor->atualizar($data, $vendedor);

        } else {
            $this->notificationError->addErro('vendedor', Response::$statusTexts[Response::HTTP_NOT_FOUND]);
            $this->notificationError->setCodigoErro(Response::HTTP_NOT_FOUND);
        }

        return $vendedorInfo;

    }

    public function buscar( $data )
    {
        $vendedor      = $this->getVendedor($data);
        $vendedorExiste = !is_null($vendedor);
        $vendedorInfo   = null;

        if ($vendedorExiste){
            $formVendedorStorage = new FormVendedorStorage($this->em);
            $regraBuscarVendedor = new RegraBuscarVendedor($this->em, $this->notificationError, $formVendedorStorage);
            $vendedorInfo = $regraBuscarVendedor->buscar($vendedor);
        } else {
            $this->notificationError->addErro('mensagem', Response::$statusTexts[Response::HTTP_NOT_FOUND]);
            $this->notificationError->setCodigoErro(Response::HTTP_NOT_FOUND);
        }

        return $vendedorInfo;

    }

    public function buscarVendedor( $data )
    {
        $formVendedorStorage =  new FormVendedorStorage($this->em);

        return $formVendedorStorage->getVendedorPorIdEVenda($data);

    }

    public function buscarTodos($venda)
    {
        $vendedorRepository = $this->em->getRepository(Vendedor::class);

        $vendedor = array_map(function ($vendedor) {
            return $vendedor->toArray();
        },$vendedorRepository->findBy(["venda"=>$venda]));

        return $vendedor;
    }

    protected function getEntidadeVendedor($data)
    {
        $idVendedor = $data['id'];

        $vendedor = new Vendedor();

        if($idVendedor > 0){

            $vendedor  = $this->em->getReference(Vendedor::class, $idVendedor);
        }

        return $vendedor;
    }

    protected function getVendedor($data)
    {
        $id               = isset($data['id']) ? $data['id'] : 0;
        $id_venda         = isset($data['venda']) ? $data['venda'] : 0;

        $venda                   = $this->em->getReference(Vendedor::class, $id_venda);
        $vendedorRepository = $this->em->getRepository(Vendedor::class);
        $vendedor          = $vendedorRepository->findOneBy(['id'=> $id,'venda'=>$venda]);

        return $vendedor;
    }

}