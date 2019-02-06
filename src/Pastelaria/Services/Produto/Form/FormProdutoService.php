<?php

namespace Pastelaria\Services\Produto\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Pastelaria\Helper\NotificationError;
use Pastelaria\Entity\Produto;

use Pastelaria\Services\Produto\Form\Storage\FormProdutoStorage;

use Pastelaria\Services\Produto\Form\Validation\ProdutoValidation;
use Pastelaria\Services\Produto\Form\Validation\FormProdutoValidation;

use Pastelaria\Services\Produto\Form\ParserProduto;

use Pastelaria\Services\Produto\Form\RegraCadastrarProduto;
use Pastelaria\Services\Produto\Form\RegraBuscarProduto;
use Pastelaria\Services\Produto\Form\RegraAtualizarProduto;
use Pastelaria\Services\Produto\Form\RegraApagarProduto;


class FormProdutoService
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
        $formProdutoStorage = new FormProdutoStorage($this->em);

        $formProdutoValidation = new FormProdutoValidation();
        $formProdutoValidation->setNotificationErrors($this->notificationError);

        $mensagemValidation = new ProdutoValidation();
        $mensagemValidation->setFormNotificationPanel($this->notificationError);
        $mensagemValidation->setFormValidation($formProdutoValidation);

        $parseProduto = new ParserProduto();

        $formProduto = new RegraCadastrarProduto();
        $formProduto->setProdutoValidation($mensagemValidation);
        $formProduto->setParserProduto($parseProduto);
        $formProduto->setFormProdutoStorage($formProdutoStorage);

        $produto = $this->getEntidadeProduto($data);
        $produtoInfo = $formProduto->cadastrar($data, $produto);

        return $produtoInfo;

    }

    public function apagar( $data )
    {
        $produto       = $this->getProduto($data);
        $produtoExiste  = !is_null($produto);
        $produtoInfo    = null;

        if (!$produtoExiste){
            $this->notificationError->addErro('produto', Response::$statusTexts[Response::HTTP_NOT_FOUND]);
            $this->notificationError->setCodigoErro(Response::HTTP_NOT_FOUND);
        } else {
            $formProdutoStorage = new FormProdutoStorage($this->em);


            $formProduto = new RegraApagarProduto($formProdutoStorage, $this->notificationError);

            $produtoInfo = $formProduto->apagar($produto);
        }

        return $produtoInfo;

    }

    public function atualizar( $data )
    {
        $produto      = $this->getProduto($data);
        $produtoExiste = !is_null($produto);
        $produtoInfo   = null;

        if ($produtoExiste){

            $formProdutoStorage = new FormProdutoStorage($this->em);

            $formProdutoValidation = new FormProdutoValidation();
            $formProdutoValidation->setNotificationErrors($this->notificationError);

            $produtoValidation = new ProdutoValidation();
            $produtoValidation->setFormNotificationPanel($this->notificationError);
            $produtoValidation->setFormValidation($formProdutoValidation);

            $parseProduto               = new ParserProduto();

            $formProduto = new RegraAtualizarProduto();
            $formProduto->setProdutoValidation($produtoValidation);
            $formProduto->setParserProduto($parseProduto);
            $formProduto->setFormProdutoStorage($formProdutoStorage);


            $produtoInfo = $formProduto->atualizar($data, $produto);

        } else {
            $this->notificationError->addErro('produto', Response::$statusTexts[Response::HTTP_NOT_FOUND]);
            $this->notificationError->setCodigoErro(Response::HTTP_NOT_FOUND);
        }

        return $produtoInfo;

    }

    public function buscar( $data )
    {
        $produto      = $this->getProduto($data);
        $produtoExiste = !is_null($produto);
        $produtoInfo   = null;

        if ($produtoExiste){
            $formProdutoStorage = new FormProdutoStorage($this->em);
            $regraBuscarProduto = new RegraBuscarProduto($this->em, $this->notificationError, $formProdutoStorage);
            $produtoInfo = $regraBuscarProduto->buscar($produto);
        } else {
            $this->notificationError->addErro('mensagem', Response::$statusTexts[Response::HTTP_NOT_FOUND]);
            $this->notificationError->setCodigoErro(Response::HTTP_NOT_FOUND);
        }

        return $produtoInfo;

    }

    public function buscarProduto( $data )
    {
        $formProdutoStorage =  new FormProdutoStorage($this->em);

        return $formProdutoStorage->getProdutoPorIdEVenda($data);

    }

    public function buscarTodos($venda)
    {
        $produtoRepository = $this->em->getRepository(Produto::class);

        $produto = array_map(function ($produto) {
            return $produto->toArray();
        },$produtoRepository->findBy(["venda"=>$venda]));

        return $produto;
    }

    protected function getEntidadeProduto($data)
    {
        $idProduto = $data['id'];

        $produto = new Produto();

        if($idProduto > 0){
            $produto  = $this->em->getReference(Produto::class, $idProduto);
        }

        return $produto;
    }

    protected function getProduto($data)
    {
        $id               = isset($data['id']) ? $data['id'] : 0;
        $id_venda         = isset($data['venda']) ? $data['venda'] : 0;

        $venda                   = $this->em->getReference(Venda::class, $id_venda);
        $produtoRepository = $this->em->getRepository(Produto::class);
        $produto          = $produtoRepository->findOneBy(['id'=> $id,'venda'=>$venda]);

        return $produto;
    }

}