<?php

use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ImageCourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\MyCourseController;
use App\Http\Controllers\ReviewController;
use App\Models\Chapter;
use App\Models\Mentor;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('mentors', 'MentorController@create');



// Route::get('mentors', [MentorController::class, 'index']);
// Route::get('mentors/{id}', [MentorController::class, 'show']);
// Route::post('mentors', [MentorController::class, 'create']);
// Route::put('mentors/{id}', [MentorController::class, 'update']);
// Route::delete('mentors/{id}', [MentorController::class, 'destroy']);

Route::controller(MentorController::class)->group(function() {
    Route::get('mentors', 'index')->name('list.mentor');
    Route::get('mentors/{id}', 'show')->name('detail.mentor');
    Route::post('mentors',  'create')->name('mentor.create');
    Route::put('mentors/{id}',  'update')->name('mentor.update');
    Route::delete('mentors/{id}',  'destroy')->name('mentor.delete');
});

Route::controller(CourseController::class)->group(function() {
    Route::get('courses', 'index')->name('list.courses');
    Route::get('courses/{id}', 'show')->name('course.detail');
    Route::post('courses',  'create')->name('course.create');
    Route::put('courses/{id}',  'update')->name('course.update');
    Route::delete('courses/{id}',  'destroy')->name('course.delete');
});

Route::controller(ChapterController::class)->group(function() {
    Route::get('chapters', 'index')->name('list.chapter');
    Route::get('chapters/{id}', 'show')->name('detail.chapter');
    Route::post('chapters',  'create')->name('chapter.create');
    Route::put('chapters/{id}',  'update')->name('chapter.update');
    Route::delete('chapters/{id}',  'destroy')->name('chapter.delete');
});

Route::controller(LessonController::class)->group(function() {
    Route::get('lessons', 'index')->name('list.lessons');
    Route::get('lessons/{id}', 'show')->name('detail.lessons');
    Route::post('lessons', 'create')->name('lessons.create');
    Route::put('lessons/{id}',  'update')->name('lessons.update');
    Route::delete('lessons/{id}',  'destroy')->name('lessons.delete');
});

Route::controller(ImageCourseController::class)->group(function() {
    // Route::get('lessons', 'index')->name('list.lessons');
    // Route::get('lessons/{id}', 'show')->name('detail.lessons');
    Route::post('image-courses', 'create')->name('imageCourse.create');
    // Route::put('lessons/{id}',  'update')->name('lessons.update');
    Route::delete('image-courses/{id}',  'destroy')->name('imageCourse.delete');
});

Route::controller(MyCourseController::class)->group(function() {
    Route::get('my-courses', 'index')->name('list.myCourses');
    // Route::get('lessons/{id}', 'show')->name('detail.lessons');
    Route::post('my-courses', 'create')->name('myCourses.create');
    Route::post('my-courses/premium', 'createPremiumAccess')->name('myCourses.createPremiumAccess');
    // Route::put('lessons/{id}',  'update')->name('lessons.update');
    // Route::delete('image-courses/{id}',  'destroy')->name('imageCourse.delete');
});

Route::controller(ReviewController::class)->group(function() {
    Route::post('reviews', 'create')->name('reviews.create');
    Route::put('reviews/{id}', 'update')->name('reviews.update');
    Route::delete('reviews/{id}', 'destroy')->name('reviews.delete');
});