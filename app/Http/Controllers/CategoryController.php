<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories =Category::with(['vendors','courses'])->get();

        foreach($categories as $category)
        {
            $category->noAction = 0;

            if (count($category->vendors ) > 0 || count($category->courses ) > 0 ){

                $category->noAction = 1;

            }
        }

        return response()->json($categories);
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
            'name' => 'required|string|max:100|unique:categories,name',
            'category_code' => 'required|string|max:100|unique:categories,category_code',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $category = Category::create([
            'name' => $request->name,
            'category_code' => $request->category_code,
        ]);
        return response()->json($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
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
            'name' => 'required|string|max:100|unique:categories,name' . ($id ? ",$id" : ''),
            'category_code' => 'required|string|max:100|unique:categories,category_code' . ($id ? ",$id" : ''),

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'category_code' => $request->category_code,
        ]);

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::with(['vendors','courses'])->find($id);

        if (count($category->vendors ) == 0 && count($category->courses ) == 0 ){

            $category->delete();

            return response()->json('deleted success');

        }else{

            return response()->json('this category have children');
        }
    }

    /**
     * get vendor in category by id
     *
     */

    public function getVendorInCategoryById($id)
    {
        $categories = Vendor::where([
            ['active',1],
            ['category_id',$id]
        ])->get();
        return response()->json($categories);
    }

    /**
     * Activation Category.
     */

    public function activationCategory($id)
    {

        $categories = Category::findOrFail($id);
        if ($categories->active == 1){

            $categories->update([
                'active' => 0,
            ]);

        }else{

            $categories->update([
                'active' => 1,
            ]);
        }

        return response()->json($categories);
    }

    /**
     * get Active Category.
     */
    public function getActiveCategory()
    {
        $categories = Category::where('active',1)->get();
        return response()->json($categories);
    }

    /**
     * get des Active Category.
     */
    public function getDeactivateCategory()
    {
        $categories = Category::where('active',0)->get();
        return response()->json($categories);
    }

}
