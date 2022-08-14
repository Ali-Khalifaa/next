<?php

namespace App\Http\Controllers;

use App\Models\InterestingLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InterestingLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $interestings = InterestingLevel::all();
        foreach($interestings as $interesting)
        {
            $interesting->noAction = 0;
            if(count($interesting->leads) > 0) 
            {
                $interesting->noAction = 1;
            }
        }
        return response()->json($interestings);
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
            'name' => 'required|string|max:100|unique:interesting_levels',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $InterestingLevels = InterestingLevel::create([
            'name'=>$request->name
        ]);

        return response()->json($InterestingLevels);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $interestingLevel = InterestingLevel::findOrFail($id);
        return response()->json($interestingLevel);
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
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }

        $interestingLevel = InterestingLevel::findOrFail($id);
        $interestingLevel->update([
            'name'=>$request->name
        ]);

        return response()->json($interestingLevel);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $interestingLevel = InterestingLevel::findOrFail($id);
        $interestingLevel->delete();

        return response()->json('deleted success');
    }

    /**
     * Activation Interesting Levels.
     */

    public function activationInterestingLevel($id)
    {

        $interestingLevel = InterestingLevel::findOrFail($id);
        if ($interestingLevel->active == 1){
            $interestingLevel->update([
                'active' => 0,
            ]);

        }else{

            $interestingLevel->update([
                'active' => 1,
            ]);
        }

        return response()->json($interestingLevel);
    }

    /**
     * get Active Interesting Level.
     */
    public function getActiveInterestingLevel()
    {
        $interestingLevel = InterestingLevel::where('active',1)->get();
        return response()->json($interestingLevel);
    }

    /**
     * get des Active Interesting Level.
     */
    public function getDeactivateInterestingLevel()
    {
        $interestingLevel = InterestingLevel::where('active',0)->get();
        return response()->json($interestingLevel);
    }
}
