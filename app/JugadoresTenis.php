<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JugadoresTenis extends Model
{
    protected $connection = 'sqlsrv4';
    protected $table = 'jugadores';
    protected $primaryKey = 'NoNL';
}
