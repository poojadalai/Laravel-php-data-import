<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use DB;
use App\Models\CreditCard;
use Carbon\Carbon;
use App\Models\Customer;;
use Illuminate\Support\Facades\Validator;
use File;

class CustomerJSONProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filename;
        $extension;
         
        //Get the uploaded filename and extenion
        if(file_exists(base_path("database/data/challenge.json"))) {
            $filename = File::get(base_path("database/data/challenge.json"));
            $extension = pathinfo(base_path("database/data/challenge.json"), PATHINFO_EXTENSION);
        }else if(file_exists(base_path("database/data/cs.csv")))  {
            $filename = file_get_contents(database_path("data\cs.csv"));
            $extension = pathinfo(base_path("database/data/cs.csv"), PATHINFO_EXTENSION);
        }else{
            echo "Please provide file with correct filename and extension";
        }
       

        //Get JSON Decode of the file
        if($extension === "json")
            $data = $this->readJSON($filename);
        else if($extension === "csv")
             $data = $this->convertCsvToJson($filename);
        else{
           echo "Source file extension is diffrent than accepted format.";       
        }
        
        //store data to the customer table 
        try{
            foreach ($data as $obj){ 
                // calculate the age from 'date_of_birth' 
                $age = $this->calculateAge($obj-> date_of_birth);

                // pattern to check credit card number is identical digit in sequence                    
                $creditCardMatch =  $this->filterCreditCard($obj-> credit_card->number);           

               if((($age >= 18 && $age <= 65) ||  $obj-> date_of_birth === null) && $creditCardMatch === 1){        
                    $this->storeCustomerData($obj);      
                }
            }
        }
        catch(Exception $exception){
           abort(403, 'Server Error : Data is failed to save');
        }  
    }

     //read json file
    public function readJSON($filename){         
        $data = json_decode($filename); //decoding the JSON content
        return $data;
    }

    public function convertCsvToJson($filename){        
        $row = array();
        $final_data = array();
        $data_array = array_map("str_getcsv", explode("\n", $filename));   

        //shift first row of array
        $header = array_shift($data_array);
        foreach($header as $label){
            $row[] = $label;
        }
        $count = count($data_array) - 1;
        for($j = 0; $j < $count; $j++)
        {
            $data = array_combine($row, $data_array[$j]);
            $final_data[$j] = $data;
        }   
        //encode and decode file to json
        $data = json_encode($final_data);
        $final_csv= json_decode($data);  
        return $final_csv ;
    }
    //calculate age 
    public function calculateAge($birthDate){               
        $birthDate = str_replace("/", "-", $birthDate);       
        $calculatedAge = Carbon::parse($birthDate); 
        return $calculatedAge->age;
    }

    //check credit card number to match pattern
    public function filterCreditCard($credit_card){
        $re = '/(\d)\1{2}/m';               
        $creditCardMatch =  preg_match($re, $credit_card);
        return $creditCardMatch;
    }

    // store Customer data to table
    public function storeCustomerData($obj){
        if($obj){
            Customer::create(array(
                'name' => $obj-> name,
                'address' => $obj-> address,
                'checked' => $obj-> checked,
                'description' => $obj-> description,
                'interest' => $obj-> interest,
                'date_of_birth' => $obj-> date_of_birth,
                'email' => $obj-> email,
                'account' => $obj-> account,
                'credit_card' => $this->storeCreditCard($obj-> credit_card) 
            ));
        }
        
    }
    //store credicard details to table
    public function storeCreditCard($obj) {
        if($obj){
        $cc_id = CreditCard::create(array(
                'type' => $obj-> type,
                'number' => $obj-> number,
                'name' => $obj-> name,
                'expirationDate' => $obj-> expirationDate
            ))->id;
        return $cc_id;
        }

    }
}
