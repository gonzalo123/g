<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use G\Db;

class AppController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function homeAction()
    {
        return "AppController::home";
    }

    public function helloAction($name)
    {
        return "home" . $name . $this->request->get('xxx', '1');
    }

    public function dbAction($name, Db $db, JsonResponse $response)
    {
        $conn  = $db->getConnection();
        $sql = "SELECT * FROM test";
        $stmt = $conn->query($sql);

        return $response->setData($stmt->fetchAll());
    }

    public function renderTwigAction($name, \Twig_Environment $view)
    {
        return "hello {$name}" . $view->render('x/a.twig');
    }
}