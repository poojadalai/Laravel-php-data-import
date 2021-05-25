<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\CreditCard;
use App\Models\Industry;
use League\Csv\Reader;     
use Carbon\Carbon;
use App\Jobs\CustomerJSONProcess;
use File;


class CustomerController extends Controller
{
    public function store(Request $request)
    { 
       // dispatching created job 
        CustomerJSONProcess::dispatch();
    }
}