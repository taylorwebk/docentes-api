<?php
namespace Controllers;

use \Models\Response;
use \Models\Utils;
use \Models\Administrador as Admin;
use \Models\Auxiliar;
use \Models\Docente;
use \Models\Materia;
use \Models\Comentario;


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
      if ($data['grado']==='Aux') {
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
  public static function subjectList(){
      $materias = Materia::all();
      return Response::OK('Lista de materias', 'Lista de materias mostrada correctamente!' , $materias);
  }
  

  public static function univList($id){
        $auxiliar = Auxiliar::find($id);
        $sum = 0;
        $nrocom = $auxiliar->comentarios()->count();
        $res= $auxiliar->comentarioscf->map(function($comentario){
               
            if($comentario->comentario_id==null){

            return [
                'id'            => $comentario->id,
                'cont'          => $comentario->cont,
                'val'           => $comentario->pivot->val,
                'fecha'         => $comentario->pivot->fecha,
                'hora'         => $comentario->pivot->hora,
                'subComentarios'=> $comentario->subcomentarios
            ];
            }
        });
        $res = $res->filter(function($r) {
            if($r == null) {
                return false;
            }
            return true;
        });

           
            $auxiliar->comentarios->each(function($comentario) use (&$sum) {
                $sum += $comentario->pivot->val;
            });
            $respuesta = [
                'id'            => $auxiliar->id,
                'nombres'       => $auxiliar->nombres,
                'apellidos'     => $auxiliar->apellidos,
                'puntuacion'    => $nrocom>0?($sum/$nrocom):0,
                'nroCom'        => $nrocom,
                'comentarios'   => $res
            ];
        return Response::OK('Auxiliares y comentarios', 'Auxiliares y comentarios correctamente!' , $respuesta);
  }

  public static function teachList($id){
    
    $teacher = Docente::find($id);
    $sum = 0;
    $nrocom = $teacher->comentarios()->count();
    $res= $teacher->comentarioscf->map(function($comentario){
           
        if($comentario->comentario_id==null){

        return [
            'id'            => $comentario->id,
            'cont'          => $comentario->cont,
            'val'           => $comentario->pivot->val,
            'fecha'         => $comentario->pivot->fecha,
            'hora'          => $comentario->pivot->hora,
            'subComentarios'=> $comentario->subcomentarios
        ];
        }
    });
    $res = $res->filter(function($r) {
        if($r == null) {
            return false;
        }
        return true;
    });
        $teacher->comentarios->each(function($comentario) use (&$sum) {
            $sum += $comentario->pivot->val;
        });
        $respuesta = [
            'id'            => $teacher->id,
            'nombres'       => $teacher->nombres,
            'apellidos'     => $teacher->apellidos,
            'puntuacion'    => $nrocom>0?($sum/$nrocom):0,
            'nroCom'        => $nrocom,
            'comentarios'   => $res
        ];
    return Response::OK('Docentes y comentarios', 'Docentes y comentarios correctamente!' , $respuesta);
}
public static function teachersList(){
    $teachers = Docente::with(['comentarios','materias'])->get();
    $teachersres = $teachers->map(function($teacher){
        $sum = 0;
        $cont=0;
        $listMat=[];
        $nrocom = $teacher->comentarios()->count();
    
        $teacher->comentarios->each(function($comentario) use (&$sum){
        $sum += $comentario->pivot->val;
        });
        $teacher->materias->each(function($materia)use(&$listMat,&$cont){
            
            $listMat[$cont]=$materia->sigla;
            $cont++;
        });
        //en matt se almacenan todas las materias
        $matt=implode(",",$listMat);
        return [
            'id'            => $teacher->id,
            'nombres'       => $teacher->nombres,
            'apellidos'     => $teacher->apellidos,
            'grado'         => $teacher->grado,
            'materias'      => $matt,
            'puntuacion'    => $nrocom>0?($sum/$nrocom):0,
            'nroCom'        => $nrocom
        ];
    });
    return Response::OK('Lista docentes', 'Lista docentes mostrada correctamente!' , $teachersres);
  }
public static function assistantsList(){
     
    $auxs = Auxiliar::with('comentarios','materias')->get();
    $auxsres = $auxs->map(function($aux){
        $sum = 0;
        $cont=0;
        $listMat=[];
        $nrocom = $aux->comentarios()->count();
        $aux->comentarios->each(function($comentario) use (&$sum){
        $sum += $comentario->pivot->val;
    });
    $aux->materias->each(function($materia)use(&$listMat,&$cont){
            
        $listMat[$cont]=$materia->sigla;
        $cont++;
    });
        //en matt se almacenan todas las materias
        $matt=implode(",",$listMat);
        return [
            'id'            => $aux->id,
            'nombres'       => $aux->nombres,
            'apellidos'     => $aux->apellidos,
            'materias'      => $matt,
            'puntuacion'    => $nrocom>0?($sum/$nrocom):0,
            'nroCom'        => $nrocom
        ];
    });
    return Response::OK('Lista auxiliares', 'Lista auxiliares mostrada correctamente!' , $auxsres);

}
public static function topList(){
    $teachers = Docente::with(['comentarios','materias'])->get();
    $teachersres = $teachers->map(function($teacher){
        $sum = 0;
        $cont=0;
        $listMat=[];
        $nrocom = $teacher->comentarios()->count();
    
        $teacher->comentarios->each(function($comentario) use (&$sum){
        $sum += $comentario->pivot->val;
        });
        $teacher->materias->each(function($materia)use(&$listMat,&$cont){
            
            $listMat[$cont]=$materia->sigla;
            $cont++;
        });
        //en matt se almacenan todas las materias
        $matt=implode(",",$listMat);
        return  [
            'id'            => $teacher->id,
            'nombres'       => $teacher->nombres,
            'apellidos'     => $teacher->apellidos,
            'grado'         => $teacher->grado,
            'puntuacion'    => $nrocom>0?($sum/$nrocom):0,
        ];
        

    });
    $auxs = Auxiliar::with('comentarios','materias')->get();
    $auxsres = $auxs->map(function($aux){
        $sum = 0;
        $cont=0;
        $listMat=[];
        $nrocom = $aux->comentarios()->count();
        $aux->comentarios->each(function($comentario) use (&$sum){
        $sum += $comentario->pivot->val;
    });
    $aux->materias->each(function($materia)use(&$listMat,&$cont){
            
        $listMat[$cont]=$materia->sigla;
        $cont++;
    });
        //en matt se almacenan todas las materias
        $matt=implode(",",$listMat);
        return [
            'id'            => $aux->id,
            'nombres'       => $aux->nombres,
            'apellidos'     => $aux->apellidos,
            'materias'      => $matt,
            'puntuacion'    => $nrocom>0?($sum/$nrocom):0,
            'nroCom'        => $nrocom
        ];
    });
    $teachersres=$teachersres->sortByDesc('puntuacion')->values()->take(5);
    $auxsres=$auxsres->sortByDesc('puntuacion')->values()->take(5);
    $top= [
        'top_docentes'=>$teachersres,
        'top_auxiliares'=>$auxsres
    ];
   
    
    return Response::OK('Top docentes y auxiliares', 'Top  mostrado correctamente!' , $top);

}
public static function addComentarioT($data) {
    $fields=['id','cont','val','comentario_id','nick'];
    if (!Utils::validateData($data, $fields)) {
      return Response::BadRequest(Utils::implodeFields($fields));
    }
    if ($data['comentario_id']!="null") {
        $coment = Comentario::create([
          'nick'   => $data['nick'],
          'cont'   => $data['cont'],
          'comentario_id' => $data['comentario_id'],
          'fecha' => date('Y-m-d'),
          'hora' => date('H:i:s')
        ]);
        return Response::OK('Se agrego el subcomentario', 'REgistrado exitosamente', $coment);
    } else {
        $coment = Comentario::create([
            'nick'   => $data['nick'],
            'cont'   => $data['cont'],
            'fecha' => date('Y-m-d'),
             'hora' => date('H:i:s')
        ]);
        $docente = Docente::find($data['id']);
        $docente->comentarios()->attach($coment->id, [
            'val'   => $data['val']
        ]);
        return Response::OK('Se agrego el comentario', 'REgistrado exitosamente', $coment);
    }          
  }


  public static function addComentarioA($data) {
    $fields=['id','cont','val','comentario_id','nick'];
    if (!Utils::validateData($data, $fields)) {
      return Response::BadRequest(Utils::implodeFields($fields));
    }
    if ($data['comentario_id']!="null") {
        $coment = Comentario::create([
          'nick'   => $data['nick'],
          'cont'   => $data['cont'],
          'comentario_id' => $data['comentario_id'],
          'fecha' => date('Y-m-d'),
          'hora' => date('H:i:s')
        ]);
        return Response::OK('Se agrego el subcomentario', 'REgistrado exitosamente', $coment);
    } else {
        $coment = Comentario::create([
            'nick'   => $data['nick'],
            'cont'   => $data['cont'],
            'fecha' => date('Y-m-d'),
             'hora' => date('H:i:s')
        ]);
        $auxiliar = Auxiliar::find($data['id']);
        $auxiliar->comentarios()->attach($coment->id, [
            'val'   => $data['val']
        ]);
        return Response::OK('Se agrego el comentario', 'REgistrado exitosamente', $coment);
    }          
  }


/* public static function topAssistantsList(){
    $auxs = Auxiliar::with('comentarios','materias')->get();
    $auxsres = $auxs->map(function($aux){
        $sum = 0;
        $cont=0;
        $listMat=[];
        $nrocom = $aux->comentarios()->count();
        $aux->comentarios->each(function($comentario) use (&$sum){
        $sum += $comentario->pivot->val;
    });
    $aux->materias->each(function($materia)use(&$listMat,&$cont){
            
        $listMat[$cont]=$materia->sigla;
        $cont++;
    });
        //en matt se almacenan todas las materias
        $matt=implode(",",$listMat);
        return [
            'id'            => $aux->id,
            'nombres'       => $aux->nombres,
            'apellidos'     => $aux->apellidos,
            'materias'      => $matt,
            'puntuacion'    => $nrocom>0?($sum/$nrocom):0,
            'nroCom'        => $nrocom
        ];
    });
    $auxsres=$auxsres->sortByDesc('puntuacion')->values()->take(5);
    return Response::OK('Top auxiliares', 'Top auxiliares mostrado correctamente!' , $auxsres);
} */

}