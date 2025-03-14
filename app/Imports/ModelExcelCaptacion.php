<?php


namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class ModelExcelCaptacion implements ToArray
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

                $dateInput = trim($row[8]);


                if(!is_numeric($dateInput)){

                        $parto = $dateInput;

                }else{

                    
                        $parto = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($row[8]))->format('d/m/Y');


                }




                $this->data[] = array($row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$parto);  
            }
            

           

        }
    }

    public function getArray(): array
    {
        return $this->data;
    }
}
