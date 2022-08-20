<?php

namespace App\Http\Controllers\Api\Label;

use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        //Get all labels for authenticated user
        $authenticatedUser = $request->user();
        $labels = $authenticatedUser->labels()->get();
        return response()->json($labels);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
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
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        //
        $authenticatedUser = $request->user();
        $authenticatedUser->labels()->where('id', $id)->update(['name'=> $request['name']]);

        return response()->json([
            'message' => "Label successfully updated",
            'label' => $request['name']
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        //
        $authenticatedUser = $request->user();
        $authenticatedUser->labels()->where('id', $id)->delete();

        return response()->json([
            'message' => "Label successfully deleted",
        ],200);
    }
}
