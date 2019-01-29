<?php

namespace Pastelaria;

use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Pastelaria\Helper\Cookie;

class App implements ControllerProviderInterface
{

    private $noAuthCalls = [];
    private $authenticationService = null;
    private $authorizationService = null;

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        Controller\App\Home::addRoutes($controllers, $this);

        $controllers->before(function (Request $request) use ($app) {

            $method = $request->getMethod();
            $uri    = $request->get('_route');

            if ($method == 'OPTIONS') {
                return new Response('', Response::HTTP_NO_CONTENT);
            }

            $uriCode = $method . "::" . $uri;

            $appAuthSrv = $this->getAuthenticationService();

            if (!$appAuthSrv) {
                return;
            }

            $token = "";
            $timestamp = 0;

            $cookie = $this->getCookie($request, $app);

            if (!is_null($cookie)) {
                list($token, $timestamp) = explode("|", $cookie);
            }

            $appAuthSrv->setEntityManager($app['orm.em']);

            if ( $appAuthSrv->authenticate($token) ) {

                $loginAccessToken = $appAuthSrv->getLoginAccessToken();
                $login = $loginAccessToken->getLogin();

                if (is_null($login)) {
                    return $this->getResponseUnauthorized($request, $app);
                }
                $app['current_user'] = $login;
                $app['token'] = $loginAccessToken;
                $app['empresa_atual'] = $loginAccessToken->getEmpresaAtual();
                $app['usuario_informacoes'] = $login->toArray();


            } elseif (!in_array($uriCode, $this->getNoAuthCalls())) {
                return $this->getResponseUnauthorized($request, $app);
            }
        });

        return $controllers;
    }

    public function getNoAuthCalls()
    {
        return $this->noAuthCalls;
    }

    public function setNoAuthCall($uri)
    {
        if (!in_array($uri, $this->noAuthCalls)) {
            $this->noAuthCalls[] = $uri;
        }
    }

    public function getAuthorizationService()
    {
        return $this->authorizationService;
    }

    public function setAuthorizationService(AuthenticationInterface $authorizationService)
    {
        return $this->authorizationService = $authorizationService;
    }

    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    public function setAuthenticationService($authenticationService)
    {
        return $this->authenticationService = $authenticationService;
    }

    private function getCookie(Request $request, Application $app)
    {
        return Cookie::getCookie($app, $request);
    }

    private function getResponseUnauthorized(Request $request, Application $app)
    {
        $response = $app->redirect('/home');

        if (strpos($request->headers->get('Accept'), 'application/json') !== false) {
            $response = new JsonResponse(Response::$statusTexts[Response::HTTP_UNAUTHORIZED], Response::HTTP_UNAUTHORIZED);
        }
        return $response;
    }
}
