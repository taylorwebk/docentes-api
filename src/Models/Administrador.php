<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
class Administrador extends Model
{
    protected $guarded = [];
    protected $table = 'administrador';
    protected $hidden = ['password', 'id'];
    public $timestamps = false;
}

