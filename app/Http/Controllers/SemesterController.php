<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Programme;
use App\Models\Semester;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programmes = Programme::pluck('name','id');

        $status = ['active','inactive','pending'];

        return view('semester.index',compact('programmes','status'));
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
            'name'=> 'required',
            'programme_id'=> 'required',
            'semester_number'=> 'required',
            'start_date'=> 'required',
            'end_date'=> 'required',
        ],[
            'name.required' =>'Name is required',
            'programme_id.required' =>'Programme is required',
            'semester_number.required' =>'Semester number is required',
            'start_date.required' =>'Start date is required',
            'end_date.required' =>'End date is required',
        ]);


        $data = $request->all();
        
        Semester::create($data);

        return response()->json();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $query = Semester::with('programmes');

       

        if($search = $request->search){
            $query->where('name','like',"%{$search}%");
        }

        $totalNotFiltered = Semester::count();
        $filtered = $query->count();

        $rows = $query
            ->skip($request->offset)
            ->take($request->limit)
            ->orderBy($request->sort ?? 'id', $request->order ?? 'desc')
            ->get();

        // dd($rows);
        $data = $rows->map(function ($item, $index) use($request) {
            
            return [
                'no' =>$request->input('offset',0)+ $index + 1,
                'id'=> $item->id,
                'name' => $item->name,
                'programme_id' => $item->programme_id,
                'programme_name' => $item->programmes->name,
                'semester_number' => $item->semester_number,
                'status' => $item->status,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
            ];
        });

        return response()->json([
            'total'=> $filtered,
            'unfiltered'=> $totalNotFiltered,
            'rows'=>$data,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $semester = Semester::find($id);
        return response()->json($semester);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $validated = $request->validate([
            'name'=>'required',
            'programme_id'=>'required',
            'semester_number'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'status'=>'required',
        ]);

        $semester = Semester::findOrFail($id);

        $semester->update($validated);

        return response()->json([
            'status'=> true,
            'message'=>'Semester updated successfully',
            'data'=> $semester
        ]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Semester $semester)
    {
        $isDelete = $semester->delete();

        return response()->json(['message'=>'Semester deleted successfully']);
    }
}
