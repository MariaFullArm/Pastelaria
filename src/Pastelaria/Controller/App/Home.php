<?php
namespace Pastelaria\Controller\App;

use Silex\Application;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Pastelaria\App;


class Home
{
    public static function addRoutes($routing,App $app)
    {
        $routing->get('/', array(new self(), 'defaultPage'))->bind('defaultPage');
        $routing->get('/home', array(new self(), 'home'))->bind('home');
    }

    public function defaultPage(Application $app)
    {
        return $app->redirect("/home");
    }

}