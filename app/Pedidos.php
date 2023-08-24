<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    protected $connection = 'sqlsrv5';
    protected $table = 'RHCom_Pedidos';
    protected $primaryKey = 'id';
}
