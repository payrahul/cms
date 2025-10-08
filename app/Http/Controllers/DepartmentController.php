<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('department.index');
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
        $request->validate([
           'department' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'name')->whereNull('deleted_at')
            ]
        ]);


        $department = Department::create([
            'name' => $request->department,
        ]);

        return response()->json(['department' => $department]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        
        $query = Department::query();

        if ($search = $request->search){
            $query->where('name','like',"%{$search}%");   
        }

        $totalNotFiltered = Department::count();
        $filtered = $query->count(); // Change if you add filters

        $rows = $query
            ->skip($request->offset)
            ->take($request->limit)
            ->orderBy($request->sort ?? 'id', $request->order ?? 'desc')
            ->get(['id', 'name']);

           $data = $rows->map(function($item, $index) use($request) {
            return [
                'no'=> $request->input('offset',0) + $index + 1,
                'id' => $item->id,
                'name' => $item->name ?? null,
            ];
            });

        return response()->json([
            'total'=>$filtered,
            'unfiltered_total'=>$totalNotFiltered,
            'rows'=>$data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return response()->json($department);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
             'department' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments','name')
                ->ignore($department->id)
                ->whereNull('deleted_at'),
            ],
        ]);

        $department->update([
            'name'=> $validated['department'],
        ]);

        return response()->json([
            'status'=> true,
            'message'=>'Department updated successfully',
            'data' => $department,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        // dd($department);

        $department->delete();
        return response()->json(['message'=>'Department deleted successfully']);
    }
}
