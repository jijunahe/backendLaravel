<?php
namespace App\Services;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class BitacoraService
{
    public static function registrar($accion, $tabla, $registroId = null, $detalles = [])
    {
        Bitacora::create([
            'user_id' => Auth::id(),
            'accion' => $accion,
            'tabla_afectada' => $tabla,
            'registro_id' => $registroId,
            'detalles' => json_encode($detalles),
            'fecha_evento' => now(),
        ]);
    }
}
