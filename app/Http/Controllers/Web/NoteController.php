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

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        return $this->noteUpdateService->update($id, $validatedData);
    }

    public function storeAjax(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        return $this->noteUpdateService->store($validatedData);
    }
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

    public function create()
    {
        return view('note.create');
    }

}
