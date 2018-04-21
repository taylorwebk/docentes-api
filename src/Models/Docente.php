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
}

