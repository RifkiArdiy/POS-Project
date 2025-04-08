<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    use HasFactory;

    protected $table = 'm_barang';

    protected $primaryKey = 'barang_id';

    protected $fillable = ['level_kode', 'level_nama'];

    public function users()
    {
        return $this->hasMany(UserModel::class, 'level_id');
    }

}