<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Http\Request;
use File;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->delete();
        $json = File::get("database/data/challenge.json");
        $data = json_decode($json);
        foreach ($data as $obj){
            $admin = Customer::where('email', '=', request()->get('email'))->first();
            if ($admin === null) {
                Customer::create(array(
                'name' => $obj-> name,
                'address' => $obj-> address,
                'checked' => $obj-> checked,
                'description' => $obj-> description,
                'interest' => $obj-> interest,
                'date_of_birth' => $obj-> date_of_birth,
                'email' => $obj-> email,
                'account' => $obj-> account,
                'credit_card' => $obj-> credit_card
                ));
            }
        }  

    }
}
