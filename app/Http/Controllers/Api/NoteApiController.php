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
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(
     *              required={"title", "content"},
     *
     *              @OA\Property(property="title", type="string", maxLength=255, example="Название заметки"),
     *              @OA\Property(property="content", type="string", example="Содержание заметки")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Заметка успешно создана",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="data", ref="/docs/swagger.yaml#/components/schemas/Note")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="Ошибка валидации",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="error", type="string", example="Переданы некорректные данные."),
     *              @OA\Property(property="details", type="object", example={"title": {"The title field is required."}})
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
            return response()->json(['error' => 'Внутренняя ошибка сервера: '.$e->getMessage()], 500);
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
     * @OA\Put(
     *      path="/api/notes/{id}",
     *      tags={"Notes"},
     *      summary="Обновить заметку",
     *      description="Обновляет информацию о заметке.",
     *      security={ {"sanctum": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID заметки",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(
     *              required={"title", "content"},
     *
     *              @OA\Property(property="title", type="string", maxLength=255, example="Новый заголовок"),
     *              @OA\Property(property="content", type="string", example="Новый контент заметки")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Успешное обновление",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(
     *                  property="data",
     *                  ref="/docs/swagger.yaml#/components/schemas/Note"
     *              )
     *          )
     *      ),
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
     *          response=403,
     *          description="Запрещено",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="error", type="string", example="Недостаточно прав для обновления этой заметки")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=404,
     *          description="Запись не найдена",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="error", type="string", example="Запись не найдена")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="Ошибка валидации",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="error", type="string", example="Ошибка валидации")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=500,
     *          description="Внутренняя ошибка сервера",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="error", type="string", example="Внутренняя ошибка сервера")
     *          )
     *      )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            $note = Note::findOrFail($id);

            $user = $request->user();

            if ($note->user_id !== $user->id) {
                return response()->json(['error' => 'Недостаточно прав для обновления этой заметки'], 403);
            }

            $note->title = $validatedData['title'];
            $note->content = $validatedData['content'];
            $note->save();

            return response()->json(['success' => true, 'data' => $note], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Ошибка валидации: '.$e->getMessage()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Запись не найдена'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Внутренняя ошибка сервера: '.$e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/notes/{id}",
     *      tags={"Notes"},
     *      summary="Удалить заметку",
     *      description="Удаляет заметку пользователя.",
     *      security={ {"sanctum": {} }},
     *
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID заметки",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Успешное удаление",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Заметка успешно удалена"
     *              )
     *          )
     *      ),
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
     *          response=403,
     *          description="Запрещено",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="error", type="string", example="Недостаточно прав для удаления этой заметки")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=404,
     *          description="Запись не найдена",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="error", type="string", example="Запись не найдена")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=500,
     *          description="Внутренняя ошибка сервера",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="error", type="string", example="Внутренняя ошибка сервера")
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        try {
            $note = Note::findOrFail($id);

            $user = auth()->user();

            if ($note->user_id !== $user->id) {
                return response()->json(['error' => 'Недостаточно прав для удаления этой заметки'], 403);
            }

            $note->delete();

            return response()->json(['success' => true, 'message' => 'Заметка успешно удалена'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Запись не найдена'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Внутренняя ошибка сервера: '.$e->getMessage()], 500);
        }
    }
}
