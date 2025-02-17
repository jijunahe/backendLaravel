<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Services\BitacoraService;
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API de Gestión de Empleados",
 *      description="Documentación de la API para gestionar empleados con autenticación JWT.",
 *      @OA\Contact(
 *          email="soporte@empresa.com"
 *      )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */

class AuthController extends Controller
{
    /**
     * Registro de usuario.
     */

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registrar un nuevo usuario",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="Oscar"),
     *             @OA\Property(property="email", type="string", format="email", example="oscar@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="123456"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="123456")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUz...")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Error en la validación"),
     * )
     */ 
    public function register(Request $request)
    {
        //  Usamos Validator en lugar de $this->validate()
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        //  Generar el token JWT
        $token = JWTAuth::fromUser($user);
        BitacoraService::registrar('registro', 'users', $user->id, ['mensaje' => 'Nuevo usuario registrado']);

        return response()->json(compact('user', 'token'), 201);
    }

    /*
     Logueo usuarios
    */
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Iniciar sesión con JWT",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="oscar@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="123456")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token de autenticación",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUz...")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Credenciales inválidas"),
     * )
     */ 

    public function login(Request $request)
    {
        //  Validar que el email y la contraseña están en el request
        $credentials = $request->only('email', 'password');

        //  Intentar autenticar al usuario con JWT
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }
        $user = auth()->user();
        BitacoraService::registrar('inicio de sesión', 'users', $user->id, ['mensaje' => 'El usuario ha iniciado sesión']);

        // Retornar el token JWT
        return response()->json(compact('token'));
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Cerrar sesión (Logout)",
     *     tags={"Autenticación"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sesión cerrada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sesión cerrada correctamente")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Error al cerrar sesión"),
     * )
     */
    public function logout()
    {
        try {

            $user = auth()->user();
            JWTAuth::invalidate(JWTAuth::getToken());
            BitacoraService::registrar('cierre de sesión', 'users', $user->id, ['mensaje' => 'El usuario ha cerrado sesión']);
            return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Error al cerrar sesión'], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Obtener usuario autenticado",
     *     tags={"Autenticación"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Datos del usuario autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Token inválido o no enviado"),
     * )
     */

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token expirado'], 401);
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Token no enviado'], 401);
        }

        return response()->json(compact('user'));
    }


    /**
     * @OA\Post(
     *     path="/api/refresh",
     *     summary="Renovar el token JWT",
     *     tags={"Autenticación"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Nuevo token generado",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUz...")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Token inválido"),
     *     @OA\Response(response=500, description="No se pudo refrescar el token"),
     * )
     */


    public function refresh()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            return response()->json(['token' => $newToken]);
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'No se pudo refrescar el token'], 500);
        }
    }



}
