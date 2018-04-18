<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Controllers\AdminC;

$app->get('/', function (Request $req, Response $res)
{
    $arr = "HOLA MUNDO";
    return $res->withJson($arr);
});
$app->post('/administrador', function (Request $req, Response $res) {
    $result = AdminC::addAdmin($req->getParsedBody());
    return $res->withJson($result);
});
$app->post('/admin/login', function(Request $req, Response $res){
    $result = AdminC::login($req->getParsedBody());
    return $res->withJson($result);
});
