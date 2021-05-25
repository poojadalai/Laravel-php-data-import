<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory;
    
    protected $fillable =[
        'year', 
        'industry_level',
        'industry_code',
        'industry_name',
        'unit',
        'variable_code',
    ];
}
