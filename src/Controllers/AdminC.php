<?php
namespace Controllers;

use \Models\Response;
use \Models\Utils;
use \Models\Administrador;

class AdminC
{
  public static function addAdmin($data) {
    $fields=['nombres', 'apellidos', 'correo', 'password'];
    if (!Utils::validateData($data, $fields)) {
      return Response::BadRequest(Utils::implodeFields($fields));
    }
    $admin = Administrador::create([
      'nombres'   => $data['nombres'],
      'apellidos' => $data['apellidos'],
      'correo'    => $data['correo'],
      'passw'     => password_hash($data['password'], PASSWORD_DEFAULT)
    ]);
    return Response::OK('Se agrego el admin', 'REgistrado exitosamente', $admin);
  }
  
}
