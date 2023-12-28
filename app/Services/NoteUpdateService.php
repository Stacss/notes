<?php

namespace App\Services;

use App\Models\Note;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NoteUpdateService
{
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
