<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function courseEnrollment(Request $request)
    {
        //validation...
        $this->validate($request,[
           'title' => 'required',
           'description' => 'required',
           'total_videos' => 'required'
        ]);
        //create data...
        $course = new Course();
        $course->user_id = auth()->user()->id;
        $course->title = $request->title;
        $course->description = $request->description;
        $course->total_videos = $request->total_videos;

        $course->save();
        //response...
        return response()->json([
           'status' => 1,
           'message' => 'Course enrolled successfully!!!'
        ]);
    }
    public function totalCourses()
    {
//        $totalCourses = Course::all();
        $id = auth()->user()->id;
        $totalCourses = User::find($id)->courses;
        return response()->json([
            'status'=>1,
            'message' => 'All enrolled courses',
            'data'=>$totalCourses
        ]);
    }
    public function deleteCourse($id)
    {
        $userId = auth()->user()->id;
        if(Course::where([
            'id'=>$id,
            'user_id'=>$userId
        ])->exists()){
            $courseDelete = Course::find($id);
            $courseDelete->delete();
            return response()->json([
               'status'=>1,
               'message' => 'Course deleted successfully!!!'
            ]);
        }else{
            return response()->json([
                'status'=>0,
                'message' => 'Course not found!!!'
            ]);
        }
        $deleteCourse = Course::where('id',$id)->get();
        $deleteCourse->delete();
        return response()->json([
           'status'=>1,
           'message'=>'Course deleted successfully'
        ]);
    }
}
