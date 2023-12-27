<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/register",
     *      tags={"Пользователи"},
     *      summary="Регистрация нового пользователя",
     *      description="Регистрирует нового пользователя с предоставленным именем, email и паролем.",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(
     *              required={"name", "email", "password"},
     *
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123"),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Пользователь успешно зарегистрирован",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", ref="/docs/swagger.yaml#/components/schemas/User")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="Ошибка валидации",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="error", type="object", example={"email": {"The email has already been taken."}})
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=500,
     *          description="Ошибка сервера",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="error", type="string", example="Internal Server Error")
     *          )
     *      )
     * )
     */
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            return response()->json(['success' => true, 'data' => $user], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/login",
     *      tags={"Пользователи"},
     *      summary="Авторизация пользователя",
     *      description="Авторизует пользователя на основе предоставленных учетных данных и выдает токен для дальнейшего взаимодействия",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *
     *              @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Успешная авторизация",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", type="string", example="Успешная авторизация"),
     *              @OA\Property(property="token", type="string")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Неправильные учетные данные",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="error", type="string", example="Неправильные учетные данные")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=500,
     *          description="Ошибка сервера",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="error", type="string", example="Internal Server Error")
     *          )
     *      )
     * )
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                throw new \Exception('Неправильные учетные данные', 401);
            }

            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json([
                'success' => true,
                'data' => 'Успешная авторизация',
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
