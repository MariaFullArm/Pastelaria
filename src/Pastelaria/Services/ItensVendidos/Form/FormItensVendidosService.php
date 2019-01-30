<?php

namespace Pastelaria\Services\Operadora\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Pastelaria\Helper\NotificationError;
use Pastelaria\Entity\ItensVendidos;
use Pastelaria\Entity\Venda;

use Pastelaria\Services\ItensVendidos\Form\Storage\FormItensVendidosStorage;

use Pastelaria\Services\ItensVendidos\Form\Validation\ItensVendidosValidation;
use Pastelaria\Services\ItensVendidos\Form\Validation\FormItensVendidosValidation;

use Pastelaria\Services\ItensVendidos\Form\ParserItensVendidos;

use Pastelaria\Services\ItensVendidos\Form\RegraCadastrarItensVendidos;
use Pastelaria\Services\ItensVendidos\Form\RegraBuscarItensVendidos;
use Pastelaria\Services\ItensVendidos\Form\RegraAtualizarItensVendidos;
use Pastelaria\Services\ItensVendidos\Form\RegraApagarItensVendidos;

class FormItensVendidosService
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
        $formItensVendidosStorage = new FormItensVendidosStorage($this->em);

        $formItensVendidosValidation = new FormItensVendidosValidation();
        $formItensVendidosValidation->setNotificationErrors($this->notificationError);

        $mensagemValidation = new ItensVendidosValidation();
        $mensagemValidation->setFormNotificationPanel($this->notificationError);
        $mensagemValidation->setFormValidation($formItensVendidosValidation);

        $parseItensVendidos = new ParserItensVendidos();

        $formItensVendidos = new RegraCadastrarItensVendidos();
        $formItensVendidos->setItensVendidosValidation($mensagemValidation);
        $formItensVendidos->setParserItensVendidos($parseItensVendidos);
        $formItensVendidos->setFormItensVendidosStorage($formItensVendidosStorage);

        $operadora = $this->getNovoItensVendidos($data);
        $operadoraInfo = $formItensVendidos->cadastrar($data, $operadora);

        return $operadoraInfo;

    }

    public function apagar( $data )
    {
        $operadora       = $this->getItensVendidos($data);
        $operadoraExiste = !is_null($operadora);
        $operadoraInfo   = null;

        if (!$operadoraExiste){
            $this->notificationError->addErro('operadora', Response::$statusTexts[Response::HTTP_NOT_FOUND]);
            $this->notificationError->setCodigoErro(Response::HTTP_NOT_FOUND);
        } else {
            $formItensVendidosStorage = new FormItensVendidosStorage($this->em);


            $formItensVendidos = new RegraApagarItensVendidos($formItensVendidosStorage, $this->notificationError);

            $operadoraInfo = $formItensVendidos->apagar($operadora);
        }

        return $operadoraInfo;

    }

    public function atualizar( $data )
    {
        $itens_vendidos      = $this->getItensVendidos($data);
        $itensVendidosExiste = !is_null($itens_vendidos);
        $itensVendidosInfo   = null;

        if ($itensVendidosExiste){

            $formItensVendidosStorage = new FormItensVendidosStorage($this->em);

            $formItensVendidosValidation = new FormItensVendidosValidation();
            $formItensVendidosValidation->setNotificationErrors($this->notificationError);

            $itensVendidosValidation = new ItensVendidosValidation();
            $itensVendidosValidation->setFormNotificationPanel($this->notificationError);
            $itensVendidosValidation->setFormValidation($formItensVendidosValidation);

            $parseItensVendidos               = new ParserItensVendidos();

            $formItensVendidos = new RegraAtualizarItensVendidos();
            $formItensVendidos->setItensVendidosValidation($itensVendidosValidation);
            $formItensVendidos->setParserItensVendidos($parseItensVendidos);
            $formItensVendidos->setFormItensVendidosStorage($formItensVendidosStorage);


            $itensVendidosInfo = $formItensVendidos->atualizar($data, $itens_vendidos);

        } else {
            $this->notificationError->addErro('operadora', Response::$statusTexts[Response::HTTP_NOT_FOUND]);
            $this->notificationError->setCodigoErro(Response::HTTP_NOT_FOUND);
        }

        return $itensVendidosInfo;

    }

    public function buscar( $data )
    {
        $itens_vendidos      = $this->getItensVendidos($data);
        $itensVendidosExiste = !is_null($itens_vendidos);
        $itensVendidosInfo   = null;

        if ($itensVendidosExiste){
            $formItensVendidosStorage = new FormItensVendidosStorage($this->em);
            $regraBuscarItensVendidos = new RegraBuscarItensVendidos($this->em, $this->notificationError, $formItensVendidosStorage);
            $itensVendidosInfo = $regraBuscarItensVendidos->buscar($itens_vendidos);
        } else {
            $this->notificationError->addErro('mensagem', Response::$statusTexts[Response::HTTP_NOT_FOUND]);
            $this->notificationError->setCodigoErro(Response::HTTP_NOT_FOUND);
        }

        return $itensVendidosInfo;

    }

    public function buscarItensVendidos( $data )
    {
        $formItensVendidosStorage =  new FormItensVendidosStorage($this->em);

        return $formItensVendidosStorage->getItensVendidosPorIdEVenda($data);

    }

    public function buscarTodos($venda)
    {
        $itensVendidosRepository = $this->em->getRepository(ItensVendidos::class);

        $itens_vendidos = array_map(function ($itens_vendidos) {
            return $itens_vendidos->toArray();
        },$itensVendidosRepository->findBy(["venda"=>$venda]));

        return $itens_vendidos;
    }

    protected function getNovoItensVendidos($data)
    {
        $idVenda = $data['venda'];

        if($idVenda > 0){
            $itens_vendidos = new ItensVendidos();
            $venda  = $this->em->getReference(Venda::class, $idVenda);
            $itens_vendidos->setVenda($venda);
        }else{
            $this->formNotificationError->addErro('venda', Response::$statusTexts[Response::HTTP_NOT_FOUND]);
            $this->formNotificationError->setCodigoErro(Response::HTTP_NOT_FOUND);
        }

        return $itens_vendidos;
    }

    protected function getItensVendidos($data)
    {
        $id               = isset($data['id']) ? $data['id'] : 0;
        $id_venda         = isset($data['venda']) ? $data['venda'] : 0;

        $venda                   = $this->em->getReference(Venda::class, $id_venda);
        $itensVendidosRepository = $this->em->getRepository(ItensVendidos::class);
        $itens_vendidos          = $itensVendidosRepository->findOneBy(['id'=> $id,'venda'=>$venda]);

        return $itens_vendidos;
    }
}