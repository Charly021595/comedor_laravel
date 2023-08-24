<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Platillos extends Model
{
    protected $connection = 'sqlsrv5';
    protected $table = 'RHCom_Platillo';
    protected $primaryKey = 'IdComida';

}
