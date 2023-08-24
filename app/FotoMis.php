<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FotoMis extends Model
{
    protected $connection = 'sqlsrv4';
    protected $table = 'jugadores';
    protected $primaryKey = 'NoNL';
}
