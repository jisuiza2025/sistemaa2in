<?php


namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class ModelExcelCaptacionValida implements ToArray
{
    private $data;
 

    public function __construct()
    {
        $this->data = [];
       
    }
    public function array(array $rows)
    {   

        foreach ($rows as $key=>$row) {
            

            if($key>1){

               




                $this->data[] = array($row[0],$row[1],$row[2],$row[3],$row[4]);  
            }
            

           

        }
    }

    public function getArray(): array
    {
        return $this->data;
    }
}
