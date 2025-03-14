<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


use Maatwebsite\Excel\Concerns\WithEvents;     // Registra automáticamente oyentes de eventos
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;    // el evento se genera al final del proceso de la hoja de trabajo

class ExportResumenFacturacion implements FromArray ,ShouldAutoSize,WithStyles,WithEvents
{
	protected $invoices;
	
   


    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;

       
    }

    public function array(): array
    {
        return $this->invoices;
    }


    public function styles(Worksheet $sheet)
    {
        return [
            
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],
            3    => ['font' => ['bold' => true]],

           
        ];
    }


    

    public function registerEvents(): array
       {
           return [
               AfterSheet::class => function(AfterSheet $event) {
                // fusionar celdas
                // 
                    
                  

                   $event->sheet->getDelegate()->setMergeCells([
                     'A1:BD1',
                    'A2:B2', 
                    'C2:D2',
                    'E2:F2',
                    'G2:H2',
                    'I2:J2',
                    'K2:L2',
                    'M2:N2',
                    'O2:P2',
                    'Q2:R2',
                    'S2:T2',
                    'U2:V2',
                    'W2:X2',
                    'Y2:Z2',
                    'AA2:AB2', 
                    'AC2:AD2',
                    'AE2:AF2',
                    'AG2:AH2',
                    'AI2:AJ2',
                    'AK2:AL2',
                    'AM2:AN2',
                    'AO2:AP2',
                    'AQ2:AR2',
                    'AS2:AT2',
                    'AU2:AV2',
                    'AW2:AX2',
                    'AY2:AZ2',
                    'BA2:BB2',
                    'BC2:BD2'



                    ]);

                    
                    $event->sheet->getDelegate()->getStyle('A3:BD3')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


                    $event->sheet->getDelegate()->getStyle('A2:BD2')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


                     $event->sheet->getDelegate()->getStyle('A1:BD1')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);




                    $event->sheet->getDelegate()->getStyle('A2:BD2')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('ADD8E6');


                        $event->sheet->getDelegate()->getStyle('A3:BD3')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('ADD8E6');

                          $event->sheet->getDelegate()->getStyle('A1:BD1')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('ADD8E6');
                  
                 
               },
           ];
       }



}

