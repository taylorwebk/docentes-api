<?php
namespace Middlewares;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \Models\Utils;
use \Models\Administrador as Admin;
use \Models\Response as Res;

class AdminAuth
{

  public function __invoke (Request $req, Response $res, $next) {
    $tokenarr = $req->getHeader('Authorization');
    if (count($tokenarr) == 0) {
      $respuesta = Res::BadRequest('No se reconoce el token en los headers.');
      return $res->withJson($respuesta);
    }
    $data = Utils::decodeToken($tokenarr[0]);
    if ($data == false) {
      $response = Res::Unauthorized(
        'Problemas con el Token.',
        'Hubo un problema al ingresar, por favor intentelo de nuevo.'
      );
      return $res->withJson($response);
    }
    //var_dump($next['logger']);
    $admin = Admin::Where('correo', $data->correo)->firstOrFail();
    $req = $req->withAttribute('admin', $admin);
    $res = $next($req, $res);
    // HERE GOES THE ACTIONS AFTER THE REQUEST
    return $res;
  }
}
