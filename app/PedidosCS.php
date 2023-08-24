<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidosCS extends Model
{
    protected $connection = 'sqlsrv5';
    protected $table = 'RHCom_ComedorSubsidiado';
    protected $primaryKey = 'id';
}
