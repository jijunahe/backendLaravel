<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Bitacora extends Model
{
    use HasFactory;

    protected $table = 'bitacora';

    protected $fillable = [
        'user_id',
        'accion',
        'tabla_afectada',
        'registro_id',
        'detalles',
        'fecha_evento',
    ];

    protected $casts = [
        'detalles' => 'array',
        'fecha_evento' => 'datetime',
    ];

    public $timestamps = false;

    
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
