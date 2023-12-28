<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NoteApiController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/notes",
     *      tags={"Notes"},
     *      summary="Получить все заметки пользователя",
     *      description="Отображает список всех заметок, принадлежащих зарегистрированному пользователю.",
     *      security={{ "sanctum": {} }},
     *
     *      @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *
     *                  @OA\Items(ref="/docs/swagger.yaml#/components/schemas/Note")
     *              )
     *          )
     *      ),
     *     security={ {"sanctum": {} }},
     *
     *      @OA\Response(
     *          response=401,
     *          description="Не авторизован",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="error", type="string", example="Unauthenticated.")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=500,
     *          description="Внутренняя ошибка сервера",
     *
     *          @OA\JsonContent(
     *
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
            return response()->json(['error' => 'Ошибка: '.$e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Внутренняя ошибка сервера: '.$e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/notes",
     *      tags={"Notes"},
     *      summary="Создать новую заметку",
     *      description="Создаёт новую заметку для авторизованного пользователя.",
     *      security={{ "sanctum": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"title", "content"},
     *              @OA\Property(property="title", type="string", maxLength=255, example="Название заметки"),
     *              @OA\Property(property="content", type="string", example="Содержание заметки")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Заметка успешно создана",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", ref="/docs/swagger.yaml#/components/schemas/Note")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Ошибка валидации",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Переданы некорректные данные."),
     *              @OA\Property(property="details", type="object", example={"title": {"The title field is required."}})
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
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            $user = $request->user();
            $note = new Note([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
            ]);
            $user->notes()->save($note);

            return response()->json(['success' => true, 'data' => $note], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Переданы некорректные данные.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Внутренняя ошибка сервера: ' . $e->getMessage()], 500);
        }
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
