<?php

namespace App\Http\Controllers;

use App\Imports\CourseImport;
use App\Models\Course;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CourseController extends Controller
{

    /**
     * import data excel
     */
    public function courseImport(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        Excel::import(new CourseImport, $request->file('file')->store('temp'));

        return response()->json("successfully");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::with(['coursePrices','diplomas','traningCourses','leadCourses','exam','examDegrees','interviewResults'])->get();

        foreach($courses as $course)
        {
            $course->noAction = 0;

            if (count($course->coursePrices ) > 0 || count($course->diplomas ) > 0 || count($course->traningCourses ) > 0 || count($course->leadCourses ) > 0 || count($course->exam ) > 0 || count($course->examDegrees ) > 0 || count($course->interviewResults ) > 0 ){

                $course->noAction = 1;

            }
        }

        return response()->json($courses);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:vendors,id',
//            'allow_reservation_without_schedule' => 'required|boolean',
            'course_period_description' => 'required',
            'hour_count' => 'required',
            'course_prerequisites' => 'required',
            'course_overview' => 'required',
            'course_outlines' => 'required',
            'course_code' => 'required|string|max:100|unique:courses,course_code',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $course = Course::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'vendor_id' => $request->vendor_id,
//            'allow_reservation_without_schedule' => $request->allow_reservation_without_schedule,
            'course_period_description' => $request->course_period_description,
            'hour_count' => $request->hour_count,
            'course_prerequisites' => $request->course_prerequisites,
            'course_overview' => $request->course_overview,
            'course_outlines' => $request->course_outlines,
            'course_code' => $request->course_code,
        ]);
        return response()->json($course);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::findOrFail($id);
        return response()->json($course);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:vendors,id',
//            'allow_reservation_without_schedule' => 'required|boolean',
            'course_period_description' => 'required',
            'hour_count' => 'required',
            'course_prerequisites' => 'required',
            'course_overview' => 'required',
            'course_outlines' => 'required',
            'course_code' => 'required|string|max:100|unique:courses,course_code' . ($id ? ",$id" : ''),

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $course = Course::findOrFail($id);
        $course->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'vendor_id' => $request->vendor_id,
//            'allow_reservation_without_schedule' => $request->allow_reservation_without_schedule,
            'course_period_description' => $request->course_period_description,
            'hour_count' => $request->hour_count,
            'course_prerequisites' => $request->course_prerequisites,
            'course_overview' => $request->course_overview,
            'course_outlines' => $request->course_outlines,
            'course_code' => $request->course_code,
        ]);
        return response()->json($course);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json('deleted success');
    }

    /**
     * Add images to course.
     */
    public function addImagesToCourse (Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'banner_image' => 'mimes:jpeg,jpg,png,gif|required|max:10000', // max 10000kb
            'small_image' => 'mimes:jpeg,jpg,png,gif|required|max:10000', // max 10000kb

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        // file upload

        if($request->hasFile('banner_image'))
        {
            $img = $request->file('banner_image');
            $ext = $img->getClientOriginalExtension();
            $banner_name = "Courses-banner". uniqid() . ".$ext";
            $img->move( public_path('uploads/Courses/banner/') , $banner_name);
        }
        if($request->hasFile('small_image'))
        {
            $img = $request->file('small_image');
            $ext = $img->getClientOriginalExtension();
            $small_name = "Courses-small". uniqid() . ".$ext";
            $img->move( public_path('uploads/Courses/small/') , $small_name);
        }

        $course = Course::findOrFail($id);
        $course->update([
            'banner_image' => $banner_name,
            'small_image' => $small_name
        ]);

        return response()->json($course);
    }

    /**
     * Activation course.
     */

    public function activationCourse(int $id)
    {

        $course = Course::findOrFail($id);
        if ($course->active == 1){

            $course->update([
                'active' => 0,
            ]);

        }else{

            $course->update([
                'active' => 1,
            ]);
        }

        return response()->json($course);
    }

    /**
     * get Active courses.
     */
    public function getActiveCourse()
    {
        $courses = Course::where('active',1)->get();
        return response()->json($courses);
    }

    /**
     * get des Active courses.
     */
    public function getDeactivateCourse()
    {
        $courses = Course::where('active',0)->get();
        return response()->json($courses);
    }


}
