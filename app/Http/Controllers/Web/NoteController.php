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
}
