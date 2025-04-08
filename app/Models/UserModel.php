<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 'm_user';

    protected $primaryKey = 'user_id';

    protected $fillable = ['level_id', 'username', 'nama', 'password'];

    public function level()
    {
        return $this->belongsTo(LevelModel::class, 'level_id');
    }

    public function stok()
    {
        return $this->hasMany(StokModel::class, 'user_id');
    }

    public function penjualans()
    {
        return $this->hasMany(PenjualanDetailModel::class, 'user_id');
    }

}