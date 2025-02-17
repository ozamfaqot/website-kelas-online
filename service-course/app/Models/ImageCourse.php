<?php

namespace App\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImageCourse extends Model
{
    use HasFactory;

    protected $table = 'image_courses';
    protected $fillable = ['course_id', 'image'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
    ];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
