<?php

namespace App\Http\Controllers;

use App\Models\Programme;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('department', function ($q2) use ($search) {
                        $q2->where('name','like', "%{$search}%");
                    });
                });
                
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
                    'no' => $request->input('offset', 0) + $index + 1,
                    'id'=> $item->id,
                    'department_name' => $item->department->name ?? null,
                    'programme_name' => $item->name,
                ];
            });

            return response()->json([
                'total'            => $filteredCount,        // rows after filtering (Bootstrap Table uses this)
                'unfiltered_total' => $totalNotFilteredCount, // optional extra you can use elsewhere
                'rows'             => $data
            ]);

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
        $data = $programme->only(['id','name','department_id']);
        // dd($data);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Programme $programme)
    {

        $validated = $request->validate([
            'name'=> [
                'required',
                Rule::unique('programmes','name')->ignore($programme->id),
            ],
            'department_id'=>'required|exists:departments,id'
        ]);

        Programme::find($request->id)->update([
            'name'=> $request->name,
            'department_id' => $request->department_id
        ]);

        return response()->json(['message'=>'Programme updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Programme $programme)
    {
        // dd($programme);
        $res = $programme->delete();

        // dd($res);

        return response()->json(['message'=>'Programme deleted successfully']);
    }
}
