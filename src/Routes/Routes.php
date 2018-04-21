<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Controllers\AdminC;

$app->group('/admin', function () use ($app) {
    $app->post('/docente', function (Request $req, Response $res)
    {
        $admin = $req->getAttribute('admin');
        $result = AdminC::addUnivTeach($admin, $req->getParsedBody());
        return $res->withJson($result);
    });
})->add(new \Middlewares\AdminAuth());

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
$app->get('/docaux', function(Request $req, Response $res){
    $result = AdminC::teachUnivList();
    return $res->withJson($result);
});
$app->get('/materias', function(Request $req, Response $res){
    $result = AdminC::subjectList();
    return $res->withJson($result);
});