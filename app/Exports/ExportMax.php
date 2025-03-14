<?php 

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Llamada;


class ExportMax implements FromCollection, ShouldAutoSize, WithStyles, WithEvents,WithHeadings
{
    protected $invoices;

    public function __construct($invoices) {
        $this->invoices = $invoices;
    }



     public function collection() {
        return $this->invoices; // Devuelve la colección
    }


    public function headings(): array
    {
        return [
            'NO_CIA',
            'NUMERO_CONTRATO',
            'DEUDA_ANNOS',
            'MONEDA',
            'SALDO',
            'PROXIMO_CONTACTO',
            'CODIGO',
            'TITULO',
            'COBRADOR',
            'NUMERO_BASE',
            'SERVICIO',
            'NUMERO_PRECONTRATO',
            'FECHA_VENCE',
            'VENC_VALMES',
            'CODUSU_RESPCOB',
            'PAR_IMPAR',
            'FAMILIA',
            'SITUACION',
            'CIA_SEGUROS',
            'FECHA_NACE',
            'NOMBRE_BEBE',
            'DNI_MAMA',
            'NOTIFICA_MAMA',
            'MAMA',
            'FIJO_MAMA',
            'CELULAR_MAMA',
            'MAIL_MAMA',
            'LOCALIDAD_MAMA',
            'INUBICABLE_MAMA',
            'DNI_PAPA',
            'NOTIFICA_PAPA',
            'PAPA',
            'FIJO_PAPA',
            'CELULAR_PAPA',
            'MAIL_PAPA',
            'LOCALIDAD_PAPA',
            'INUBICABLE_PAPA',
            'HEMOCULTIVO',
            'SEROLOGIA',
            'DEBITO',
            'EDAD_BEBE',
            'RESPONSABLE_CONTRATO',
            'DIRECCION_MAMA',
            'DIRECCION_PAPA',
            'UBIGEO_MAMA',
            'UBIGEO_PAPA',
            'MORA',
            'DSCTO',
            'SALDOFIN',
            'CANT_CONTRATOS',
            'ESTADO_CONTRATO',
            'FECHA_CONTRATO',
            'ADN'
        ];
    }


    public function styles(Worksheet $sheet)
    {
        return [
            1=> ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A3:BB3')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}

 ?>