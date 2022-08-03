<?php

namespace App\Http\Controllers\Api\Label;

use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //Get all labels for authenticated user
        $authenticatedUser = $request->user();
        $relationships = $authenticatedUser::with('notes', 'notes.labels')->get();
        $labels = array();

        foreach ($relationships as $userrelationship){
             foreach ($userrelationship['notes'] as $userNotes){
                 $labels[] = $userNotes['labels'];
             }
        }

        return response()->json($labels);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $authenticatedUser = $request->user();
        $authenticatedUser->labels()->create([
            'name' => $request['name']
        ]);

        return response()->json(['message'=> "Label successfully created", 'label'=> $request['name']], 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $authenticatedUser = $request->user();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
