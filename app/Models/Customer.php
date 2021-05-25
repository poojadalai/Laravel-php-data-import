<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $with = ['creditcard'];

    public function creditcard()
    {
        return $this->hasMany('App\Models\CreditCard');
    }

    protected $casts = [
        'checked' => 'boolean'
    ];

    protected $fillable =[
        'name', 
        'address',
        'checked',
        'description',
        'interest',
        'date_of_birth',
        'email',
        'account',
        'credit_card'
    ];

}
