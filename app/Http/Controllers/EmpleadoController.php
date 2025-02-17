<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Services\EmailService;
use App\Services\BitacoraService;

/**
 * @OA\Tag(name="Empleados", description="Operaciones sobre empleados")
 */
class EmpleadoController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
 
    }

    /**
     * @OA\Get(
     *     path="/api/empleados",
     *     summary="Obtener la lista de empleados con opción de filtrar por documento, nombre o email",
     *     tags={"Empleados"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="buscar",
     *         in="query",
     *         description="Filtrar empleados por documento de identidad, nombre o email",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de empleados",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="documento_identidad", type="string", example="123456789"),
     *                 @OA\Property(property="nombres", type="string", example="Juan"),
     *                 @OA\Property(property="apellidos", type="string", example="Pérez"),
     *                 @OA\Property(property="email", type="string", format="email", example="juan@example.com"),
     *                 @OA\Property(property="cargo", type="string", example="Desarrollador"),
     *                 @OA\Property(property="estado", type="string", example="activo"),
     *                 @OA\Property(property="fecha_nacimiento", type="string", format="date", example="1990-05-15"),
     *                 @OA\Property(property="fecha_ingreso", type="string", format="date", example="2022-06-01"),
     *                 @OA\Property(property="fecha_creacion", type="string", format="date-time", example="2025-02-17T03:37:53.000000Z"),
     *                 @OA\Property(property="fecha_actualizacion", type="string", format="date-time", example="2025-02-17T03:37:53.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autorizado"),
     * )
     */
    public function index(Request $request)
    {
        $query = Empleado::query();

        
        if ($request->has('buscar')) {
            $busqueda = $request->input('buscar');
            $busqueda = trim($busqueda);
            $busqueda = preg_replace('/\s+/', ' ', $busqueda);
            $elements=explode(" ",$busqueda);
            $total=count($elements);
            $query->where('documento_identidad', 'like', "%{$busqueda}%")
                  ->orWhere('nombres', 'like', "%{$busqueda}%")
                  ->orWhere('apellidos', 'like', "%{$busqueda}%")
                  ->orWhere('email', 'like', "%{$busqueda}%");
            if($total>1){
                 if($total==2){
                    $query->orWhere('nombres', 'like', "%{$elements[0]}%")
                          ->orWhere('apellidos', 'like', "%{$elements[1]}%");
                 }else if($total==4){
                    $query->orWhere('nombres', 'like', "%{$elements[0]} {$elements[1]}%")
                    ->orWhere('apellidos', 'like', "%{$elements[2]} {$elements[3]}%");
                 }else if($total==3){
                    $query->orWhere('nombres', 'like', "%{$elements[0]}%")
                    ->orWhere('apellidos', 'like', "%{$elements[1]} {$elements[2]}%");
                 }
            }
        }

        return response()->json($query->orderBy('nombres', 'asc')->get());
    }
    /**
     * @OA\Post(
     *     path="/api/empleados",
     *     summary="Crear un nuevo empleado",
     *     tags={"Empleados"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"documento_identidad","nombres","apellidos","email","cargo","fecha_nacimiento","fecha_ingreso","estado"},
     *             @OA\Property(property="documento_identidad", type="string", example="123456789"),
     *             @OA\Property(property="nombres", type="string", example="Juan"),
     *             @OA\Property(property="apellidos", type="string", example="Pérez"),
     *             @OA\Property(property="email", type="string", format="email", example="juan@example.com"),
     *             @OA\Property(property="cargo", type="string", example="Desarrollador"),
     *             @OA\Property(property="fecha_nacimiento", type="string", format="date", example="1990-05-15"),
     *             @OA\Property(property="fecha_ingreso", type="string", format="date", example="2022-06-01"),
     *             @OA\Property(property="estado", type="string", example="activo")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Empleado creado correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="documento_identidad", type="array",
     *                     @OA\Items(type="string", example="Ya existe un empleado con este documento de identidad.")
     *                 ),
     *                 @OA\Property(property="email", type="array",
     *                     @OA\Items(type="string", example="El correo electrónico ya está en uso.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autorizado"),
     * )
     */


    public function store(Request $request)
    {
        $mensajes = [
            'documento_identidad.required' => 'El documento de identidad es obligatorio.',
            'documento_identidad.unique' => 'Ya existe un empleado con este documento de identidad.',
            'email.unique' => 'El correo electrónico ya está en uso.',
        ];
        $validador = Validator::make($request->all(), [
            'documento_identidad' => 'required|string|max:20|unique:empleados',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|string|email|unique:empleados|max:255',
            'cargo' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'fecha_ingreso' => 'required|date',
            'estado' => 'required|in:activo,dado de baja,de vacaciones,permiso,licencia',
        ], $mensajes);


        if ($validador->fails()) {
            return response()->json([
                'error' => $validador->errors()
            ], 422); // Código HTTP correcto para errores de validación
        }

        $empleado = Empleado::create($request->all());
                
        $this->emailService->enviarCorreoBienvenida($empleado);
        BitacoraService::registrar('creación', 'empleados', $empleado->id, $request->all());

        return response()->json($empleado, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/empleados/{id}",
     *     summary="Obtener los datos de un empleado por su ID",
     *     tags={"Empleados"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Datos del empleado obtenidos correctamente"),
     *     @OA\Response(response=404, description="Empleado no encontrado"),
     *     @OA\Response(response=401, description="No autorizado"),
     * )
     */
    public function show($id)
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            return response()->json(['error' => 'Empleado no encontrado'], 404);
        }

        return response()->json($empleado);
    }

    /**
     * @OA\Put(
     *     path="/api/empleados/{id}",
     *     summary="Actualizar los datos de un empleado",
     *     tags={"Empleados"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del empleado a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="documento_identidad", type="string", example="987654321"),
     *             @OA\Property(property="nombres", type="string", example="Juan"),
     *             @OA\Property(property="apellidos", type="string", example="Pérez"),
     *             @OA\Property(property="email", type="string", format="email", example="juan@example.com"),
     *             @OA\Property(property="cargo", type="string", example="Gerente de TI"),
     *             @OA\Property(property="fecha_nacimiento", type="string", format="date", example="1990-05-15"),
     *             @OA\Property(property="fecha_ingreso", type="string", format="date", example="2022-06-01"),
     *             @OA\Property(property="estado", type="string", example="activo")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Empleado actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Empleado no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Empleado no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="documento_identidad", type="array",
     *                     @OA\Items(type="string", example="Este documento de identidad ya está registrado en otro empleado.")
     *                 ),
     *                 @OA\Property(property="email", type="array",
     *                     @OA\Items(type="string", example="El correo electrónico ya está en uso.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autorizado"),
     * )
     */


    public function update(Request $request, $id)
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            return response()->json(['error' => 'Empleado no encontrado'], 404);
        }
        $mensajes = [
            'documento_identidad.unique' => 'Este documento de identidad ya está registrado en otro empleado.',
            'email.unique' => 'El correo electrónico ya está en uso por otro empleado.',
        ];
        $validador = Validator::make($request->all(), [
            'documento_identidad' => 'string|max:20|unique:empleados,documento_identidad,'.$empleado->id,
            'nombres' => 'string|max:255',
            'apellidos' => 'string|max:255',
            'email' => 'string|email|max:255|unique:empleados,email,'.$empleado->id,
            'cargo' => 'string|max:255',
            'fecha_nacimiento' => 'date',
            'fecha_ingreso' => 'date',
            'estado' => 'in:activo,dado de baja,de vacaciones,permiso,licencia',
        ], $mensajes);

        if ($validador->fails()) {
            return response()->json([
                'error' => $validador->errors()
            ], 422);
        }  
        $empleado->update($request->all());
        BitacoraService::registrar('actualización', 'empleados', $empleado->id, $request->all());

        return response()->json($empleado);
    }

    /**
     * @OA\Delete(
     *     path="/api/empleados/{id}",
     *     summary="Eliminar un empleado",
     *     tags={"Empleados"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Empleado eliminado correctamente"),
     *     @OA\Response(response=404, description="Empleado no encontrado"),
     *     @OA\Response(response=401, description="No autorizado"),
     * )
     */
    public function destroy($id)
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            return response()->json(['error' => 'Empleado no encontrado'], 404);
        }

        $empleado->delete();
        BitacoraService::registrar('eliminación', 'empleados', $id, ['mensaje' => 'Empleado eliminado']);

        return response()->json(['message' => 'Empleado eliminado correctamente']);
    }
}
