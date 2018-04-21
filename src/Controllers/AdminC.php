<?php
namespace Controllers;

use \Models\Response;
use \Models\Utils;
use \Models\Administrador as Admin;
use \Models\Auxiliar;
use \Models\Docente;

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
  public static function addUnivTeach($admin, $data) {
      $fields = ['nombres', 'apellidos', 'grado', 'materias'];
      if (!Utils::validateData($data, $fields)) {
          return Response::BadRequest(Utils::implodeFields($fields));
      }
      if ($data['grado']==='') {
          $aux = Auxiliar::create([
              'nombres'     => $data['nombres'],
              'apellidos'   => $data['apellidos']
          ]);
          foreach ($data['materias'] as $idmat) {
              $aux->materias()->attach($idmat, ['estado' => 1]);
          }
          return Response::OK('Registrado', 'Auxiliar '.$aux->nombres.' registrado', null);
      } else {
          $doc = Docente::create([
              'nombres'     => $data['nombres'],
              'apellidos'   => $data['apellidos'],
              'grado'       => $data['grado']
          ]);
          foreach ($data['materias'] as $idmat) {
              $doc->materias()->attach($idmat, ['estado' => 1]);
          }
          return Response::OK('Registrado', 'Docente '.$doc->grado.' '.$doc->nombres.' registrado', null);
      }
      
  }
}
