<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'programme_id',
        'semester_number',
        'status',
        'start_date',
        'end_date'
    ];

    public function programmes(){
        return $this->belongsTo(Programme::class,'programme_id');
    }
}
