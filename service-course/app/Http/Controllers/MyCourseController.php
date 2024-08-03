<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\MyCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MyCourseController extends Controller
{

    public function index(Request $request)
    {
        // $myCourse = MyCourse::all();
        $userId = $request->query('user_id');
        $myCourses = MyCourse::query()->with('course');

        $myCourses->when($userId, function($query) use ($userId) {
            return $query->where('user_id', $userId);
        });

        return response()->json([
            'status' => 'success',
            'data' => $myCourses->get()
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'course_id' => 'required|integer',
            'user_id' => 'required|integer',
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

        // Cek course_id di database course
        $courseId = $request->input('course_id');
        $course = Course::find($courseId);
        if(!$course)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        // Cek user_id di databae user
        $userId = $request->input('user_id');
        $user = getUser($userId);
        if($user['status'] === 'error')
        {
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ], $user['http_code']);
        }

        // return 'ok';
        $isExistMyCourse = MyCourse::where('course_id', $courseId)
                                    ->where('user_id', $userId)
                                    ->exists();
        if($isExistMyCourse)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'User already taken this course'
            ], 409);
        }

        //cek apakah coursenya premium
        if($course->type === 'premium')
        {

            if($course->price === 0)
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Price cannot be nol'
                ], 405);
            }

            $order = postOrder([
                'user' => $user['data'],
                'course' => $course->toArray()
            ]);

            // echo '<pre>'.print_r($order, 1).'</pre>';

            if($order['status'] === 'error')
            {
                return response()->json([
                    'status' => $order['status'],
                    'message' => $order['message']
                ], $order['http_code']);
            }

            return response()->json([
                'status' => $order['status'],
                'data' => $order['data']
            ]);
        }
        else
        {
            $myCourse = MyCourse::create($data);

            return response()->json([
                'status' => 'success',
                'data' => $myCourse
            ]);
        }

       
    }

    public function createPremiumAccess(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $myCourse = MyCourse::create($data);
        // dd($myCourse);

        if(!$myCourse)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'ERROR'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $myCourse
        ]);
    }
}
