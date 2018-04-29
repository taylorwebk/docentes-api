<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
class Auxiliar extends Model
{
    protected $guarded = [];
    protected $table = 'auxiliar';
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

