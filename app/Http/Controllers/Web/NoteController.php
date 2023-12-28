<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Services\NoteUpdateService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class NoteController extends Controller
{
    protected $noteUpdateService;

    public function __construct(NoteUpdateService $noteUpdateService)
    {
        $this->noteUpdateService = $noteUpdateService;
    }

    /**
     * Обновляет существующую заметку.
     *
     * @param \Illuminate\Http\Request $request Запрос, содержащий данные для обновления заметки.
     * @param int $id Идентификатор (ID) существующей заметки, которую необходимо обновить.
     * @return \Illuminate\Http\JsonResponse JSON-ответ с результатом операции обновления заметки.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        return $this->noteUpdateService->update($id, $validatedData);
    }

    /**
     * Создает новую заметку через AJAX-запрос.
     *
     * @param \Illuminate\Http\Request $request Запрос, содержащий данные для создания заметки.
     * @return \Illuminate\Http\JsonResponse JSON-ответ с результатом операции создания новой заметки.
     */
    public function storeAjax(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        return $this->noteUpdateService->store($validatedData);
    }

    /**
     * Создает новую заметку на основе данных из запроса.
     *
     * @param \Illuminate\Http\Request $request Запрос, содержащий данные для создания заметки.
     * @return \Illuminate\Http\RedirectResponse Перенаправляет на домашнюю страницу с уведомлением о успешном создании заметки.
     */
    public function store(Request $request)
    {
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

        return redirect()->route('home')->with('status', 'Заметка успешно создана');
    }

    /**
     * Отображает форму для создания новой заметки.
     *
     * @return \Illuminate\View\View Представление для создания новой заметки.
     */
    public function create()
    {
        return view('note.create');
    }

}
