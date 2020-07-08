<?php

namespace Mnemosine\DataTables;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DataTablesExportExcel implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents
{
    use Exportable;

    /**
     * @var Collection
     */
    protected $collection;
    protected $photoInventory;
    protected $photoResearch;
    protected $photoInventoryPostion;
    protected $photoResearchPostion;
    protected $report;

    /**
     * DataTablesExportHandler constructor.
     *
     * @param Collection $collection
     * @param array $photoInventory
     * @param array $photoResearch
     */
    public function __construct(array $data, array $photoInventory, array $photoResearch, $photoInventoryPostion, $photoResearchPostion, $report)
    {
        //$this->collection = $collection;
        $this->data = $data;
        $this->photoInventory = $photoInventory;
        $this->photoResearch = $photoResearch;
        $this->photoInventoryPostion = $photoInventoryPostion;
        $this->photoResearchPostion = $photoResearchPostion;
        $this->report = $report;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return collect($this->data);
    }

    public function registerEvents(): array
    {
        $count = [
            count($this->data[0]), //column count
            count($this->data), //row count
        ];
        $appraisalKey = false;
        if(array_key_exists('Avalúo', $this->data[0])){
            $arrKeys = array_keys($this->data[0]);
            $appraisalKey = array_search('Avalúo', $arrKeys);
        }

        $drawings = $this->photoInventory;
        $drawingsResearch = $this->photoResearch;

        return [
            AfterSheet::class => function (AfterSheet $event) use ($count, $drawings, $drawingsResearch, $appraisalKey) {
                $firstRow = 1;
                if($this->report->lending_list){
                    $firstRow = 6;
                    // write extra rows
                    $event->sheet->setCellValue('B1', 'Listado para préstamo de obra.');
                    $event->sheet->setCellValue('A2', 'Institución solicitante:');
                    $event->sheet->setCellValue('B2', $this->report->institution);
                    $event->sheet->setCellValue('A3', 'Nombre de la exposición:');
                    $event->sheet->setCellValue('B3', $this->report->exhibition);
                    $event->sheet->setCellValue('A4', 'Fechas de exhibición:');
                    $event->sheet->setCellValue('B4', $this->report->exhibition_date_ini->locale('es_MX')->isoFormat('LL') . ' al ' . $this->report->exhibition_date_fin->locale('es_MX')->isoFormat('LL'));
                    // Styles
                    $event->sheet->getStyle('A1:B4')->getFont()->setName('Arial');
                    $event->sheet->getStyle('A1:B4')->getFont()->setSize(12);
                    $event->sheet->getStyle('B1')->getFont()->setSize(14);
                    $event->sheet->getStyle('B1')->getFont()->setBold(true);
                }
                //Freeze row
                $freezeRow = 'A' . ($firstRow+1);
                $event->sheet->freezePane($freezeRow, $freezeRow);
                // Sheet Name
                $event->sheet->setTitle("Listado");

                //Set auto width for the rest
                $columnIndex = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ');
                for ($i=0;$i<$count[0];$i++) { //iterate based on column count
                    if ($i>76) {
                        break;
                    }
                    $event->sheet->getColumnDimension($columnIndex[$i])->setWidth(29);
                }

                // Format appraisal column
                if($appraisalKey){
                    $range = $columnIndex[$appraisalKey].$firstRow.':'.$columnIndex[$appraisalKey].($count[1]+$firstRow+1);
                    $event->sheet->getStyle($range)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                }

                // Inventory photos
                if (count($drawings)) {
                    $event->sheet->getColumnDimension($columnIndex[$this->photoInventoryPostion])->setWidth(21);
                    foreach ($drawings as $key=>$drawing_path) {
                        if ($drawing_path && file_exists(public_path($drawing_path))) {
                            $coordinateRow = $key+1+$firstRow;
                            $coordinate = $columnIndex[$this->photoInventoryPostion] . $coordinateRow;
                            $event->sheet->setCellValue($coordinate, '');
                            $event->sheet->getRowDimension($coordinateRow)->setRowHeight(85);

                            $desc = $this->collection[$key]['No. inventario'] ?? "Foto de inventario";
                            $drawing = new Drawing();
                            $drawing->setName($desc);
                            $drawing->setDescription($desc);
                            $drawing->setPath(public_path($drawing_path));
                            $drawing->setHeight(100);
                            $drawing->setResizeProportional(true);
                            $drawing->setOffsetX(5);
                            $drawing->setOffsetY(5);
                            $drawing->setCoordinates($coordinate);
                            $drawing->setWorksheet($event->sheet->getDelegate());
                        }
                    }
                }
                // Reseaarch photos
                if (count($drawingsResearch)) {
                    $event->sheet->getColumnDimension($columnIndex[$this->photoResearchPostion])->setWidth(21);
                    foreach ($drawingsResearch as $key=>$drawing_path) {
                        if ($drawing_path && file_exists(public_path($drawing_path))) {
                            $coordinateRow = $key+1+$firstRow;
                            $coordinate = $columnIndex[$this->photoResearchPostion] . $coordinateRow;
                            $event->sheet->setCellValue($coordinate, '');
                            $event->sheet->getRowDimension($coordinateRow)->setRowHeight(85);

                            $desc = $this->collection[$key]['No. inventario'] ?? "Foto de investigación";
                            $drawing = new Drawing();
                            $drawing->setName($desc);
                            $drawing->setDescription($desc);
                            $drawing->setPath(public_path($drawing_path));
                            $drawing->setHeight(100);
                            $drawing->setResizeProportional(true);
                            $drawing->setOffsetX(5);
                            $drawing->setOffsetY(5);
                            $drawing->setCoordinates($coordinate);
                            $drawing->setWorksheet($event->sheet->getDelegate());
                        }
                    }
                }

                $rangeAll = 'A'.$firstRow.':'.$columnIndex[$count[0]-1].($count[1]+$firstRow);
                $rangeTitle = 'A'.$firstRow.':'.$columnIndex[$count[0]-1].$firstRow;

                // All cells styles
                // wrap cells
                $event->sheet->getStyle($rangeAll)->getAlignment()->setWrapText(true);
                // change font size
                $event->sheet->getStyle($rangeAll)->getFont()->setName('Arial');
                $event->sheet->getStyle($rangeAll)->getFont()->setSize(12);
                // vertical align top
                $event->sheet->getStyle($rangeAll)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                // borders
                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];
                $event->sheet->getStyle($rangeAll)->applyFromArray($styleArray);

                // Title styles
                // cell background
                $event->sheet->getStyle($rangeTitle)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFC10007');
                // text color
                $event->sheet->getStyle($rangeTitle)->getFont()->getColor()->setARGB('FFFFFFFF');
                // font size
                $event->sheet->getDelegate()->getStyle($rangeTitle)->getFont()->setSize(13);
                $event->sheet->getStyle($rangeTitle)->applyFromArray($styleArray);
            },
        ];
    }

    public function startCell(): string
    {
        if($this->report->lending_list){
            return 'A6';
        } else{
            return 'A1';
        }
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $first = collect($this->data)->first();
        if ($first) {
            return array_keys($first);
        }

        return [];
    }
}
