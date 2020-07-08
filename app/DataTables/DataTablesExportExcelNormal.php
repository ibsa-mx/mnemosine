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

class DataTablesExportExcelNormal implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents
{
    use Exportable;

    /**
     * @var Collection
     */
    protected $collection;
    protected $photoInventory;
    protected $photoResearch;

    /**
     * DataTablesExportHandler constructor.
     *
     * @param Collection $collection
     * @param array $photoInventory
     * @param array $photoResearch
     */
    public function __construct(array $data, array $photoInventory, array $photoResearch)
    {
        //$this->collection = $collection;
        $this->data = $data;
        $this->photoInventory = $photoInventory;
        $this->photoResearch = $photoResearch;
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
        $colsRight = 0;
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
        if(count($this->photoInventory)) $colsRight++;
        if(count($this->photoResearch)) $colsRight++;

        return [
            AfterSheet::class => function (AfterSheet $event) use ($count, $drawings, $drawingsResearch, $appraisalKey, $colsRight) {
                $firstRow = 1;
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
                    $event->sheet->setCellValue($columnIndex[$count[0]].$firstRow, 'Foto de inventario');
                    $event->sheet->getColumnDimension($columnIndex[$count[0]])->setWidth(21);
                    foreach ($drawings as $key=>$drawing_path) {
                        if ($drawing_path && file_exists(public_path($drawing_path))) {
                            $coordinateRow = $key+1+$firstRow;
                            $coordinate = $columnIndex[$count[0]] . $coordinateRow;
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
                    $event->sheet->setCellValue($columnIndex[$count[0]+$colsRight-1].$firstRow, 'Foto de investigación');
                    $event->sheet->getColumnDimension($columnIndex[$count[0]+$colsRight-1])->setWidth(21);
                    foreach ($drawingsResearch as $key=>$drawing_path) {
                        if ($drawing_path && file_exists(public_path($drawing_path))) {
                            $coordinateRow = $key+1+$firstRow;
                            $coordinate = $columnIndex[$count[0]+$colsRight-1] . $coordinateRow;
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

                $rangeAll = 'A'.$firstRow.':'.$columnIndex[$count[0]-1+$colsRight].($count[1]+$firstRow);
                $rangeTitle = 'A'.$firstRow.':'.$columnIndex[$count[0]-1+$colsRight].$firstRow;

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
        return 'A1';
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
