<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * get all Country
     */

    public function countries()
    {
        $countries = Country::all();
        return response()->json($countries);
    }

    /**
     * get all Countries with cities
     */

    public function countriesWithCities()
    {
        $countries = Country::with('cities')->get();
        return response()->json($countries);
    }

    /**
     * get all cities
     */

    public function cities()
    {
        $cities = State::all();
        return response()->json($cities);
    }

    /**
     * get cities by country id
     */

    public function getCitiesByCountryId ($id)
    {
        $cities = State::where('country_id',$id)->get();
        return response()->json($cities);
    }


}
