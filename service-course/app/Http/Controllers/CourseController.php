<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Mentor;
use App\Models\MyCourse;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $courses = Course::all();
        // dd(request(['q', 'status']));
        // $q = request('q');
        $q = $request->query('q');
        $status = $request->query('status');
        $courses = Course::query();

        $courses->when($q, function($query) use ($q) {
            return $query->whereRaw("Name LIKE '%".strtolower($q)."%'");
        });

        $courses->when($status, function($query) use ($status) {
            return $query->where('status', '=',$status);
        });
        // $courses->when($status, function($query) use ($status) {
        //     return $query->where('status', $status);
        // });

        return response()->json([
            'status' => 'success',
            'data' => $courses->paginate(10)
        ]);
    }

    public function show(string $id)
    {
        $course = Course::with('chapters.lessons')
                        ->with('mentor')
                        ->with('images')
                        ->find($id);
        // $course = Course::with(['chapters', 'mentor'])->find($id);
        if(!$course)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $reviews = Review::where('course_id', $id)->get()->toArray();
        if(count($reviews) > 0)
        {
            $userIds = array_column($reviews, 'user_id');
            // dd($userIds);
            // var_dump($userIds);
            $users = getUserByIds($userIds);
            // dd($users);
            // echo "<pre>".print_r($users, 1)."</pre>";
            // var_dump($users);
            if($users['status'] === 'error')
            {
                $reviews = [];
            }
            else
            {
                foreach($reviews as $key => $review)
                {
                    $arr = array_column($users['data'], 'id'); // [3,4] => membuat array kumpulan 'id' dari array $users['data']
                    // var_dump($arr);die();
                    $userIndex = array_search($review['user_id'], $arr);//mencari index dari $review['user_id'] pada $users['data'] ($arr)
                    $reviews[$key]['users'] = $users['data'][$userIndex];
                }
            }
        }

        $totalStudent = MyCourse::where('course_id', $id)->count();
        $videoLesson = Chapter::where('course_id', $id)->withCount('lessons')->get()->toArray();
        $totalVideo = array_sum(array_column($videoLesson, 'lessons_count'));
        // var_dump($totalVideo);die();

        $course['reviews'] = $reviews;
        $course['total_videos'] = $totalVideo;
        $course['total_student'] = $totalStudent;

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'certificate' => 'required|boolean',
            'thumbnail' => 'string|url',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,biginner,intermediate,advance',
            'description' => 'string',
            'mentor_id' => 'required|integer',
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

        // Cek mentor_id apakah ada di database
        $mentorId = $request->input('mentor_id');
        $mentor = Mentor::find($mentorId);
        if($mentor == null)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentor not found'
            ], 404);
        }
        // return response()->json([
        //     'data' => $mentor
        // ]);

        $course = Course::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'string',
            'certificate' => 'boolean',
            'thumbnail' => 'string|url',
            'type' => 'in:free,premium',
            'status' => 'in:draft,published',
            'price' => 'integer',
            'level' => 'in:all-level,biginner,intermediate,advance',
            'description' => 'string',
            'mentor_id' => 'integer',
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

        $course = Course::find($id);
        if(!$course)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        // Cek mentor_id apakah ada di database
        $mentorId = $request->input('mentor_id');
        $mentor = Mentor::find($mentorId);
        if($mentor == null)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentor not found'
            ], 404);
        }

        $course->fill($data);
        $course->save();

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::find($id);

        if(!$course)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ]);
        }

        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Course deleted successfully' 
        ]);
    }
}
