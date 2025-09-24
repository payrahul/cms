<?php

namespace App\Http\Controllers;

use App\Models\Programme;
use App\Models\Department;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::select('id','name')->get();
        
        return view('programme.index',compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'department_id'=>'required|exists:departments,id',
            'name'=> 'required|string|max:255|unique:programmes',
        ]);

        Programme::create($validated);

        return response()->json([
            'messages'=> 'Programme saved successfully'
        ]);


    }

    /**
     * Display the specified resource.
     */

    public function show(Request $request)
    {
        try {
            // Build base query with eager loading
            $query = Programme::with('department:id,name')
                ->select('id', 'name', 'department_id');

            // Apply search filter (fixed typo)
            if ($search = $request->input('search')) {
                $query->where('name', 'like', "%{$search}%");
            }

            // Count total & filtered
            $totalNotFilteredCount = Programme::count();
            $filteredCount = $query->count();

            // Pagination + Sorting
            $rows = $query
                ->orderBy($request->input('sort', 'id'), $request->input('order', 'desc'))
                ->skip($request->input('offset', 0))
                ->take($request->input('limit', 10))
                ->get();

            // Transform data
            $data = $rows->map(function ($item, $index) use ($request) {
                return [
                    'id' => $request->input('offset', 0) + $index + 1,
                    'department_name' => $item->department->name ?? null,
                    'programme_name' => $item->name,
                ];
            });

            return response()->json($data, 200);

        } catch (\Exception $e) {
            // Handle error
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching programmes.',
                'error' => $e->getMessage(), // you may hide this in production
            ], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Programme $programme)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Programme $programme)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Programme $programme)
    {
        $programme->delete();

        return response()->json(['message'=>'Programme deleted successfully']);
    }
}
