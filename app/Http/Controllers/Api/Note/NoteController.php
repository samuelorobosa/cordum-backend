<?php

namespace App\Http\Controllers\Api\Note;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\NoReturn;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        //display all notes of user
        $authenticatedUser = $request->user();
        $userNotes = $authenticatedUser->notes()->with('labels')->latest()->get(['id', 'body']);

        return response()->json([
            'notes' => $userNotes
        ],200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        //Create a new note
        $authenticatedUser = $request->user();

        $newNote = $authenticatedUser->notes()->create([
            'body' => $request['body']
        ]);

        if (!$newNote){
            return response()->json([
                'message' => "Note could not be created",
            ], 500);
        }

        return response()->json([
           'note' => $newNote
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, $id): JsonResponse
    {
        //Get a specific note
        $authenticatedUser = $request->user();
        $note[] = $authenticatedUser->notes()->where('id', $id)->get('body');

        return response()->json([
            'notes' => $note
        ],200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        //Update specific note
        $authenticatedUser = $request->user();
        $authenticatedUser->notes()->where('id', $id)->update(['body'=> $request['body']]);

        return response()->json([
            'body'=> $request['body']
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        //Delete note
        $authenticatedUser = $request->user();
        $authenticatedUser->notes()->where('id', $id)->delete();

        return response()->json([
            'message' => 'Note successfully deleted'
        ], 200);
    }

    /**
     * Attach Labels to Specified Note.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function syncLabel(Request $request, int $id): JsonResponse
    {
        //Sync labels to note
        Note::findOrFail($id)->labels()->sync([...$request['labels']]);
        return response()->json([
            'message' => "Sync Successful"
        ], 200);
    }

}
