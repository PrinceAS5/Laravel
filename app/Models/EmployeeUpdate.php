<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'textUpdate',
        'imageUpdate',
        'pdfUpdate',
        'department',
    ];
}
