<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Progress;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/progress",
     *      *  *      tags={"Progress"},

     *     summary="Add New progress",
     *    
     *  
     *     @OA\Parameter(
     *         name="performance",
     *         in="query",
     *         description="user performance",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="measurements",
     *         in="query",
     *         description="user measurements",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="weight",
     *         in="query",
     *         description="User Weight",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="User created successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'weight' => 'required',
            'measurements' => 'required',
            'performance' => 'required',
        ]);

        $user = Auth::user();

        $progress = Progress::create([
            'user_id' => $user->id,
            'weight' => $request->weight,
            'measurements' => $request->measurements,
            'performance' => $request->performance,
        ]);

        return response()->json(['message' => 'Progress saved successfully', 'progress' => $progress], 200);
    }
    /**
     * @OA\Get(
     *     path="/api/showProgress",
     *  *      tags={"Progress"},

     *     summary="Get Progress Details",
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearer_token":{}}}
     * )
     */
    public function show()
    {
        $user = Auth::user();

        $progress = Progress::where('user_id', $user->id)->get();

        return response()->json([
            'statut' => 'success',
            'message' => 'Progress data retrieved successfully',
            'progress' => $progress,
        ], 200);
    }
    /**
     * @OA\Delete(
     *      path="/api/progress/{id}",
     *      tags={"Progress"},
     *      operationId="deleteProgress",
     *      summary="Delete Progress",
     *      description="Deletes a Progress by ID",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the progress to delete",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      ),
     * )
     */
    public function destroy($id)
    {
        $progress = Progress::findOrFail($id);
        if ($progress->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $progress->delete();
        return response()->json(['message' => 'Progress deleted successfully'], 200);
    }
    /**
     * @OA\Put(
     *     path="/api/progress/{id}",
     *      *  *      tags={"Progress"},

     *     summary="Update progress status",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the progress to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Fields to update",
     *         @OA\JsonContent(
     *             @OA\Property(property="Newstatut", type="string", description="New status for the progress")
     *         )
     *     ),
     *     @OA\Response(response="200", description="progress status updated successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'weight' => 'required',
            'measurements' => 'required',
            'performance' => 'required',
        ]);

        $progress = Progress::findOrFail($id);

        if ($progress->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $progress->update([
            'weight' => $request->weight,
            'measurements' => $request->measurements,
            'performance' => $request->performance,
        ]);

        return response()->json(['message' => 'Progress updated successfully', 'progress' => $progress], 200);
    }

    public function editProgress($id)
    {
        $progress = Progress::findOrFail($id);
        return response()->json(['message' => 'Progress edit sent', 'progress' => $progress], 200);
    }
    /**
     * @OA\Put(
     *     path="/api/progress/{id}/complete",
     *      *  *      tags={"Progress"},

     *     summary="Update progress complete",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the progress to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Fields to update",
     *         @OA\JsonContent(
     *             @OA\Property(property="Newstatut", type="string", description="New status for the progress")
     *         )
     *     ),
     *     @OA\Response(response="200", description="progress status updated successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function complete($id)
    {
        $progress = Progress::findOrFail($id);

        if ($progress->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $progress->update(['status' => 'Completed']);
        return response()->json(['message' => 'Progress status updated to Completed', 'progress' => $progress], 200);
    }
    /**
     * @OA\Get(
     *     path="/api/progress/history",
     *      *  *      tags={"Progress"},

     *     summary="Get Progress history",
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearer_token":{}}}
     * )
     */
    public function history()
    {
        $user = Auth::user();
        $progressHistory = Progress::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return response()->json(['message' => 'Progress history retrieved successfully', 'progressHistory' => $progressHistory], 200);
    }
}
