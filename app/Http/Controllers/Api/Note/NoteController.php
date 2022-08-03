<?php

namespace App\Http\Controllers\Api\Note;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //display all notes of user
        $authenticatedUser = $request->user();
        $userNotes = $authenticatedUser->notes()->get(['id', 'body']);

        return response()->json([
            'notes' => $userNotes
        ],200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
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
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        //Get a specific note
        $authenticatedUser = $request->user();
        $note = $authenticatedUser->notes()->where('id', $id)->get('body');

        return response()->json([
            'note' => $note
        ],200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
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
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        //Delete note
        $authenticatedUser = $request->user();
        $authenticatedUser->notes()->where('id', $id)->delete();

        return response()->json([
            'message' => 'Note successfully deleted'
        ], 200);
    }

}
