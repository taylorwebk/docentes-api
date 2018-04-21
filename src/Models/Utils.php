<?php
namespace Models;
use \Firebase\JWT\JWT;
class Utils 
{
  public static function validateData($data, $fields)
  {
      foreach ($fields as $value) {
          if (! isset($data[$value])) {
              return false;
          }
      }
      return true;
  }
  public static function implodeFields($fields) {
      return 'No se reconocen uno o varios de los campos: '. implode(', ', $fields);
  }
  public static function generateToken($id, $correo) {
      $token = [
          "id"        => $id,
          "correo"    => $correo
      ];
      $tokenstr = JWT::encode($token, PRIVATEKEY, 'HS512');
      return (string) $tokenstr;
  }
  public static function decodeToken($tokenstr) {
      try {
          $data = JWT::decode($tokenstr, PRIVATEKEY, ['HS512']);
      } catch(\Exception $e) {
        return false;
      }
      return $data;
  }
}
