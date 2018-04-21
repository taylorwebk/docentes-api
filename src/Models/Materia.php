<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
class Materia extends Model
{
    protected $guarded = [];
    protected $table = 'materia';
    public $timestamps = false;
    public function auxiliares() {
      return $this->belongsToMany('\Models\Auxiliar');
    }
    public function docentes() {
      return $this->belongsToMany('\Models\Docente');
    }
}