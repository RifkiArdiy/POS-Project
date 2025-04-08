<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanModel extends Model
{
    use HasFactory;
    
    protected $table = 't_penjualan';

    protected $primaryKey = 'penjualan_id';
}
