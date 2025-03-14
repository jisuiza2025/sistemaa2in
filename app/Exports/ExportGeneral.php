<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


use Maatwebsite\Excel\Concerns\WithEvents;     // Registra automáticamente oyentes de eventos
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;    // el evento se genera al final del proceso de la hoja de trabajo



class ExportGeneral implements FromArray ,ShouldAutoSize,WithStyles,WithEvents
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
            
            3    => ['font' => ['bold' => true]],

           
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
   
                $event->sheet->getDelegate()->getStyle('A3:BB3')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
   
            },
        ];
    }


}

