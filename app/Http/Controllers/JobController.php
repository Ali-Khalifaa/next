<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::all();

        foreach($jobs as $job)
        {
            $job->noAction = 0;

            if(count($job->employees) > 0) 
            {
                $job->noAction = 1;
            }
            
        }


        return response()->json($jobs);
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
            'name' => 'required|string|max:100|unique:jobs',
            'Allow_adding_to_sales_team' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $job = Job::create([
            'name' => $request->name,
            'Allow_adding_to_sales_team' => $request->Allow_adding_to_sales_team
        ]);

        return response()->json($job);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = Job::findOrFail($id);

        return response()->json($job);
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
            'Allow_adding_to_sales_team' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $job = Job::findOrFail($id);
        $job->update([
            'name'=>$request->name,
            'Allow_adding_to_sales_team' => $request->Allow_adding_to_sales_team
        ]);

        return response()->json($job);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $job = Job::findOrFail($id);
       
        if (count($job->employees ) == 0  ){

            $job->delete();

            return response()->json('deleted success');

        }else{

            return response()->json('this Job have employees');
        }
    }

    /**
     * Activation job.
     */

    public function activationJob($id)
    {
        $job = Job::findOrFail($id);
        if ($job->active == 1){

            $job->update([
                'active' => 0,
            ]);

        }else{

            $job->update([
                'active' => 1,
            ]);
        }

        return response()->json($job);
    }

    /**
     * get Active job.
     */
    public function getActiveJob()
    {
        $job = Job::where('active',1)->get();
        return response()->json($job);
    }

    /**
     * get des Active job.
     */
    public function getDeactivateJob()
    {
        $job = Job::where('active',0)->get();
        return response()->json($job);
    }
}
