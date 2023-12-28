<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteApiController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/notes",
     *      tags={"Notes"},
     *      summary="Получить все заметки пользователя",
     *      description="Отображает список всех заметок, принадлежащих зарегистрированному пользователю.",
     *      security={{ "sanctum": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="/docs/swagger.yaml#/components/schemas/Note")
     *              )
     *          )
     *      ),
     *     security={ {"sanctum": {} }},
     *      @OA\Response(
     *          response=401,
     *          description="Не авторизован",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Unauthenticated.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Внутренняя ошибка сервера",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Внутренняя ошибка сервера: Server Error")
     *          )
     *      )
     * )
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $notes = $user->notes()->get();

            return response()->json(['success' => true, 'data' => $notes], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Ошибка: ' . $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Внутренняя ошибка сервера: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
