<?php

namespace Pastelaria;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Api implements ControllerProviderInterface
{
    
    private $noAuthCalls = [];
    private $authenticationService = null;
    private $authorizationService = null;
    
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        Controller\Api\ProdutosController::addRoutes($controllers);
        
        $controllers->match("{url}", function ($url) use ($app) {
            return new Response('', 204);
        })->assert('url', '.*')->method("OPTIONS");

        $controllers->before(function (Request $request) use ($app) {

            $method = $request->getMethod();

            if ($method == 'OPTIONS') {
                return new Response('', Response::HTTP_NO_CONTENT);
            }

            $appAuthSrv = $this->getAuthenticationService();

            if (!$appAuthSrv) {
                return;
            }

            $uri     = $request->get('_route');
            $uriCode = $method . "::" . $uri;

            $token = str_replace("Bearer ", "", $request->headers->get("Authorization"));

            $appAuthSrv->setEntityManager($app['orm.em']);

            if (!$appAuthSrv->authenticate($token)  && !in_array($uriCode, $this->getNoAuthCalls())) {
                return $this->getResponseUnauthorized($request, $app);
            }

            $app['token'] = $appAuthSrv->getToken();

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
    
    private function getResponseUnauthorized(Request $request, Application $app)
    {
        $response = new Response(Response::$statusTexts[Response::HTTP_UNAUTHORIZED], Response::HTTP_UNAUTHORIZED, ["Content-type" => "text/plain"]);
        
        foreach ($request->getAcceptableContentTypes() as $contentType) {
            if ($contentType == 'text/plain') {
                break;
            }
            
            if ($contentType == 'application/json') {
                $response = new JsonResponse(['erro' => Response::$statusTexts[Response::HTTP_UNAUTHORIZED]], Response::HTTP_UNAUTHORIZED);
                break;
            }
        }
        return $response;
    }
}
