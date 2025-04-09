<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user';

    protected $primaryKey = 'user_id';

    protected $fillable = ['level_id', 'username', 'nama', 'password'];

    protected $hidden = ['password'];
    
    protected $cast = ['password' => 'hashed'];

    public function level(): BelongsTo //Menunjukkan bahwa setiap user memiliki relasi belongsTo dengan tabel LevelModel, dihubungkan melalui level_id.
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    public function stok(): HasMany {
        return $this->hasMany(StokModel::class, 'user_id', 'user_id');
    }

    public function penjualan(): HasMany {
        return $this->hasMany(PenjualanModel::class, 'user_id', 'user_id');
    }

}