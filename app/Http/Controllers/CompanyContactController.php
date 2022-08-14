<?php

namespace App\Http\Controllers;

use App\Models\CompanyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyContactController extends Controller
{
    /**
     * get company contact by company id
     */
    public function companyContactByCompanyId($id)
    {
        $companyContact = CompanyContact::where('company_id','=',$id)->get();

        return response()->json($companyContact);
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
            'title' => 'required|string|max:100',
            'mobile' => 'required|unique:company_contacts',
            'company_id' => 'required|exists:companies,id',
            'email' => 'required|unique:company_contacts',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $companyContacts = CompanyContact::create($request->all());

        return response()->json($companyContacts);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $companyContacts = CompanyContact::findOrFail($id);
        return response()->json($companyContacts);
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
            'title' => 'required|string|max:100',
            'mobile' => 'required',
            'company_id' => 'required|exists:companies,id',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors,422);
        }
        $companyContacts = CompanyContact::findOrFail($id);

        $companyContacts->update($request->all());

        return response()->json($companyContacts);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $companyContacts = CompanyContact::findOrFail($id);
        $companyContacts->delete();
        return response()->json("deleted successfully");
    }
}
