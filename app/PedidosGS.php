<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidosGS extends Model
{
    protected $connection = 'sqlsrv5';
    protected $table = 'RHCom_comedorGreenSpot';
    protected $primaryKey = 'Id';
}
