<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotesRequest;
use App\Notes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Exception;

class NotesController extends Controller
{
    public function index()
    {
        $result = Notes::orderBy('created_at', 'desc')->get();
        if (sizeof($result) === 0) {
            return response()->json(['error' => 'Nothing to get']);
        }

        try {
            return response()->json(['notes' => $result, 'error' => null]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Nothing to get'], 404);
        }
    }

    public function store(NoteRequest $request)
    {
        try {
            return response()->json(['note' => Notes::create($request->all()), 'error' => null]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Error to insert'], 404);
        }
    }

    public function update(NoteRequest $request, $id)
    {
        try {
            Notes::findOrFail($id)->update($request->all());
            return response()->json(['note' => Notes::where('id', $id)->get()->first(), 'error' => null]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'unknown Id'], 404);
        }
    }

    public function show($id)
    {
        try {
            return response()->json(['note' => Notes::findOrFail($id), 'error' => null]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'unknown Id'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            Notes::findOrFail($id)->delete();
            return response()->json(['error' => null]);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'unknown Id'], 404);
        }
    }
}