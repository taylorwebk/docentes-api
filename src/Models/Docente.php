<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
class Docente extends Model
{
    protected $guarded = [];
    protected $table = 'docente';
    public $timestamps = false;
    public function materias() {
        return $this->belongsToMany('\Models\Materia');
    }
    public function comentarios() {
        return $this->belongsToMany('\Models\Comentario')->withPivot('val');
    }
    public function comentarioscf() {
        return $this->belongsToMany('\Models\Comentario')->withPivot(['val', 'fecha', 'hora']);
    }

}

