<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
class Comentario extends Model
{
    protected $guarded = [];
    protected $table = 'comentario';
    public $timestamps = false;
    protected $hidden = ['comentario_id'];

    public function subcomentarios() {
        return $this->hasMany('\Models\Comentario');
    }
}

