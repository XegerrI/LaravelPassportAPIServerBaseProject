<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $test = auth()->user()->test;
        
        return response()->json([
            'success' => true,
            'data' => $test
        ]);
    }
    
    public function show($id)
    {
        $test = auth()->user()->test()->find($id);
        
        if (!$test) {
            return response()->json([
                'success' => false,
                'message' => 'Test is not available! '
            ], 400);
        }
        
        return response()->json([
            'success' => true,
            'data' => $test->toArray()
        ], 400);
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'detail' => 'required'
        ]);
        
        $test = new Test();
        $test->name = $request->name;
        $test->detail = $request->detail;
        
        if (auth()->user()->test()->save($test))
            return response()->json([
                'success' => true,
                'data' => $test->toArray()
            ]);
            else
                return response()->json([
                    'success' => false,
                    'message' => 'Test could not be added!'
                ], 500);
    }
    
    public function update(Request $request, $id)
    {
        $test = auth()->user()->test()->find($id);
        
        if (!$test) {
            return response()->json([
                'success' => false,
                'message' => 'Test could not be found!'
            ], 400);
        }
        
        $updated = $test->fill($request->all())->save();
        
        if ($updated)
            return response()->json([
                'success' => true
            ]);
            else
                return response()->json([
                    'success' => false,
                    'message' => 'Test could not be updated!'
                ], 500);
    }
    
    public function destroy($id)
    {
        $test = auth()->user()->test()->find($id);
        
        if (!$test) {
            return response()->json([
                'success' => false,
                'message' => 'Test could not be found!'
            ], 400);
        }
        
        if ($test->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Test could not be deleted!'
            ], 500);
        }
    }
}
