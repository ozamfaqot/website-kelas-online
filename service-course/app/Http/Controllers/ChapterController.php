<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $chapter = Chapter::all();

        // filter chapter by id
        $courseId = $request->query('course_id');
        $chapters = Chapter::query();

        $chapters->when($courseId, function($query) use ($courseId) {
            return $query->where('course_id', $courseId);
        });
        // dd($chapters->get());

        // $chapters->when($courseId, function($query) use ($courseId) {
        //     return $query->where('chapter_id', $courseId);
        // }); 

        // if(!$chapters)
        // {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Chapter not found'
        //     ], 404);
        // }

        return response()->json([
            'status' => 'success',
            'data' => $chapters->get()
        ]);
    }

    // Detail chapter
    public function show(string $id)
    {
        $chapter = Chapter::find($id);
        if(!$chapter)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Chapter not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'course_id' => 'required|integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);
        if($validator->fails())
        {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        // Cek apakah course_id ada tidak d database
        $courseId = $request->input('course_id');
        $course = Course::find($courseId);
        if(!$course)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $chapter = Chapter::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $chapter 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'string',
            'course_id' => 'integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);
        if($validator->fails())
        {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        // find chapter
        $chapter = Chapter::find($id);
        if(!$chapter)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Chapter not found'
            ], 404);
        }

        // Cek apakah course_id ada tidak d database
        $courseId = $request->input('course_id');
        $course = Course::find($courseId);
        if(!$course)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $chapter->fill($data);
        $chapter->save();

        return response()->json([
            'status' => 'success',
            'data' => $chapter 
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $chapter = Chapter::find($id);
        if(!$chapter)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Chapter not found'
            ], 404);
        }

        $chapter->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Chapter deleted successfully' 
        ]);
    }
}
