<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bitacora;
use App\Models\User;

/**
 * @OA\Tag(name="Bitácora", description="Consultar registros de la bitácora del sistema")
 */
class BitacoraController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bitacora",
     *     summary="Obtener registros de la bitácora con opción de filtrar por nombre o email de usuario",
     *     tags={"Bitácora"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="usuario",
     *         in="query",
     *         description="Filtrar por nombre o email del usuario",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de eventos registrados en la bitácora",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="usuario", type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Oscar"),
     *                     @OA\Property(property="email", type="string", example="oscar@example.com")
     *                 ),
     *                 @OA\Property(property="accion", type="string", example="inicio de sesión"),
     *                 @OA\Property(property="tabla_afectada", type="string", example="users"),
     *                 @OA\Property(property="registro_id", type="integer", example=2),
     *                 @OA\Property(property="detalles", type="object",
     *                     @OA\Property(property="mensaje", type="string", example="El usuario ha iniciado sesión")
     *                 ),
     *                 @OA\Property(property="fecha_evento", type="string", format="date-time", example="2025-02-17T03:37:53.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autorizado"),
     * )
     */
    public function index(Request $request)
    {
        $query = Bitacora::with('usuario:id,name,email')->orderBy('fecha_evento', 'desc');

        if ($request->has('usuario')) {
            $usuarioFiltro = $request->input('usuario');
            $query->whereHas('usuario', function ($q) use ($usuarioFiltro) {
                $q->where('name', 'like', "%{$usuarioFiltro}%")
                  ->orWhere('email', 'like', "%{$usuarioFiltro}%");
            });
        }

        return response()->json($query->get());
    }
}
