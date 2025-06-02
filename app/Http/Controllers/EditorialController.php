<?php

namespace App\Http\Controllers;

use App\Models\Editorial;
use Illuminate\Http\Request;

class EditorialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $editorials = Editorial::paginate(10);
        return response()->json($editorials);
    }

    public function store(Request $request){
        $editorial = new Editorial();
        $editorial->name = $request->name;
        $editorial->country = $request->country;
        $editorial->website = $request->website;
        $editorial->save();
        $response =[
            'code' => 200,
            'message' => 'Editorial created successfully',
            'editorial' => $editorial
        ];
        return response()->json($response);
    }

    public function show($id){
        $editorial = Editorial::find($id);
        if(!$editorial){
            return response()->json(['message' => 'Editorial not found'], 404);
        }
        $response =[
            'code' => 200,
            'message' => 'Editorial found successfully',
            'editorial' => $editorial
        ];
        return response()->json($response);
    }
    
    public function update(Request $request, $id){
        $editorial = Editorial::find($id);
        if(!$editorial){
            return response()->json(['message' => 'Editorial not found'], 404);
        }
        $editorial->name = $request->name;
        $editorial->country = $request->country;
        $editorial->website = $request->website;
        $editorial->save();
        $response =[
            'code' => 200,
            'message' => 'Editorial updated successfully',
            'editorial' => $editorial
        ];
        return response()->json($response);
    }

    public function destroy($id){
        $editorial = Editorial::find($id);
        if(!$editorial){
            return response()->json(['message' => 'Editorial not found'], 404);
        }
        $editorial->delete();
        $response =[
            'code' => 200,
            'message' => 'Editorial deleted successfully'
        ];
        return response()->json($response);
    }

    public function showByName(Request $request){
        $name = $request->name;
        $editorial = Editorial::where('name', $name)->first();
        if(!$editorial){
            return response()->json(['message' => 'Editorial not found'], 404);
        }
        $response =[
            'code' => 200,
            'message' => 'Editorial found successfully',
            'editorial' => $editorial
        ];
        return response()->json($response);
    }

    public function showByCountry(Request $request){
        $country = $request->country;
        $editorial = Editorial::where('country', $country)->first();
        if(!$editorial){
            return response()->json(['message' => 'Editorial not found'], 404);
        }
        $response =[
            'code' => 200,
            'message' => 'Editorial found successfully',
            'editorial' => $editorial
        ];
        return response()->json($response);
    }
}
