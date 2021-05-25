<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndustryController extends Controller
{

    public function storeCSV(Request $request)
    {
        //Open our CSV file using the fopen function.
        $fh = fopen(database_path("data\csvFile.csv"), 'r');
        
        //Setup a PHP array to hold our CSV rows.
        $csvData = array();

        //skip first row 
        $junk = fgetcsv($fh,2000,",");
        $csvv = json_encode($csvData);
        //Loop through the rows in our CSV file and add them to db
        while (($data = fgetcsv($fh, 0, ",")) !== FALSE) {
            $csvData[] = $row;
            $csv_data = new Industry();
            $csv_data->year = $data [0];
            $csv_data->Industry_level = $data [1];
            $csv_data->Industry_code = $data [2];
            $csv_data->Industry_name = $data [3];
            $csv_data->Units = $data [4];
            $csv_data->Variable_code = $data [5];
            $csv_data->save ();
        }
    }
}
