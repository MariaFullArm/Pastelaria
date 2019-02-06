<?php

namespace Pastelaria\Controller\Api;

use Silex\Application;


class ProdutosController
{


    public static function addRoutes($routing)
    {
        $routing->post('/produto' , array(new self() , 'insertProduto'))->bind('api_post_produto');
        $routing->get('/produto/{id_produto}' , array(new self() , 'getProduto'))
            ->assert('id_produto' , '\d+')
            ->value('id_produto' , 0)
            ->bind('api_get_produto');


    }

    public function insertProduto(Application $app)
    {
        /*$server = $app['oauth_server'];
        $response = $app['oauth_response'];
        $request = $app['request'];

        try {

            $temPermissao = $server->verifyResourceRequest(BridgeRequest::createFromRequest($request) , $response , self::SCOPE_ALERTAS_ENVIAR);
            $formNotificationError = new NotificationError();
            $alertaInfo = [];
            if ($temPermissao) {

                $data = RequestParamsParser::toArray($request);
                $formAlerta = new FormAlertaService($formNotificationError , $app['orm.em']);
                $alertaInfo = $formAlerta->publicarPacote($data, $app['rabbit.producer']['pacotes']);
            }

            $this->setResponse($response , $temPermissao , $formNotificationError , $app['translator'] , $alertaInfo , Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            $response = $this->getResponseError($app , $ex);
        }

        return $response;*/
    }

    public function getProduto(Application $app)
    {

    }

    private function setResponse($response , $temPermissao , $formNotificationError , $translator , $responseData , $responseCode)
    {
        if ($temPermissao && !$formNotificationError->hasErrors()) {
            $response->setStatusCode($responseCode);
            $response->setData($responseData);
        } elseif ($temPermissao && $formNotificationError->hasErrors()) {
            $errors = $formNotificationError->getErrors($translator);
            $response->setStatusCode($formNotificationError->getCodigoErro());
            $response->setData(["erros" => $errors]);
        }
    }

    private function getResponseError($app , $ex)
    {
        $app['logger']->critical($ex->getMessage());
        return new JsonResponse([] , Response::HTTP_INTERNAL_SERVER_ERROR);
    }

}
