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
}

