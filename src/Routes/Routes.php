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

$app->get('/doc/{id}', function(Request $req, Response $res, $args){
    $result = AdminC::teachList($args['id']);
    return $res->withJson($result);
});
$app->get('/auxi/{id}', function(Request $req, Response $res, $args){
    $result = AdminC::univList($args['id']);
    return $res->withJson($result);
});

$app->get('/materias', function(Request $req, Response $res){
    $result = AdminC::subjectList();
    return $res->withJson($result);
});
$app->get('/doc', function(Request $req, Response $res){
    $result = AdminC::teachersList();
    return $res->withJson($result);
});
$app->get('/auxi', function(Request $req, Response $res){
    $result = AdminC::assistantsList();
    return $res->withJson($result);
});
$app->get('/top5doc-aux', function(Request $req, Response $res){
    $result = AdminC::topList();
    return $res->withJson($result);
});

