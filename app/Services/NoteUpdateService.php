<?php

namespace App\Services;

use App\Models\Note;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NoteUpdateService
{
    /**
     * Создает новую заметку на основе валидированных данных.
     *
     * @param array $validatedData проверенные и валидные данные для создания заметки
     *
     * @return \Illuminate\Http\JsonResponse JSON-ответ с результатом операции создания новой заметки
     */
    public function store($validatedData)
    {
        try {
            $user = Auth::user();
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
     * Обновляет заметку на основе переданных валидированных данных.
     *
     * @param int   $id            идентификатор заметки, которую необходимо обновить
     * @param array $validatedData проверенные и валидные данные для обновления заметки
     *
     * @return \Illuminate\Http\JsonResponse JSON-ответ с результатом операции обновления заметки
     */
    public function update($id, $validatedData)
    {
        try {
            $note = Note::findOrFail($id);

            $user = Auth::user();

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
}
