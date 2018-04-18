<?php
namespace Controllers;

use \Models\Response;
use \Models\Utils;
use \Models\Administrador as Admin;

class AdminC
{
  public static function addAdmin($data) {
    $fields=['nombres', 'apellidos', 'correo', 'password'];
    if (!Utils::validateData($data, $fields)) {
      return Response::BadRequest(Utils::implodeFields($fields));
    }
    $admin = Admin::create([
      'nombres'   => $data['nombres'],
      'apellidos' => $data['apellidos'],
      'correo'    => $data['correo'],
      'passw'     => password_hash($data['password'], PASSWORD_DEFAULT)
    ]);
    return Response::OK('Se agrego el admin', 'REgistrado exitosamente', $admin);
  }
  public static function Login($data)
  {
      $fields = ['correo', 'password'];
      if (!Utils::validateData($data, $fields)) {
          return Response::BadRequest(
              Utils::implodeFields($fields)
          );
      }
      $admin = Admin::Where('correo', $data['correo'])->first();
      if(!$admin) {
          return Response::Unauthorized(
              'correo inválido.',
              'El Correo ingresado no ha sido identificado'
          );
      }
      if (password_verify($data['password'], $admin->passw)) {
          $tokenstr = Utils::generateToken($admin->id, $admin->correo);
          $hora = (int)date('G');
          $saludo = '';
          if ($hora <= 12) {
              $saludo = 'Buenos días ';
          } else {
              $saludo = $hora <= 18 ? 'Buenas tardes ' : 'Buenas Noches ';
          }
          return Response::OKWhitToken(
              'Login correcto',
              $saludo.$admin->nombres,
              $tokenstr,
              $admin
          );
      } else {
          return Response::Unauthorized(
              'correo o password inválidos.',
              'Verifique que los datos de ingreso sean correctos.'
          );
      }
  }
  
}
