<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyReportExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $totalAmountPaid = 0;
        $totalPlotValue = 0;
        $modifiedData = [];
        foreach ($this->data as $row) {
            switch ($row['status']) {
                case 1:
                    $status = 'Development Charges';
                    break;
                case 2:
                    $status = 'Plot Amount';
                    break;
                case 3:
                    $status = 'Full Payment';
                    break;
                case 4:
                    $status = 'Registered';
                    break;
                case 5:
                    $status = 'Cancelled';
                    break;
                default:
                    $status = 'Unknown';
            }

            $totalAmountPaid += $row['amount_paid'];
            $totalPlotValue += $row['total_amount'];
            $modifiedData[] = [
                'Project' => $row['project'],
                'Plot No' => $row['plot_no'],
                'Status' => $status,
                'Marketer Name' => $row['name'],
                'Plot Value' => $row['total_amount'],
                'Total Sqft' => $row['total_sqft'],
                'Amount Paid' => $row['amount_paid'],
            ];
        }
        $modifiedData[] = [
            'Project' => '',
            'Plot No' => '',
            'Status' => '',
            'Marketer Name' => '',
            'Plot Value' => '',
            'Total Sqft' => '',
            'Amount Paid' => '',
        ];
        $modifiedData[] = [
            'Project' => '',
            'Plot No' => '',
            'Status' => '',
            'Marketer Name' => '',
            'Plot Value' => 'Total Plot Value: ' . $totalPlotValue,
            'Total Sqft' => '',
            'Amount Paid' => '',
        ];
        $modifiedData[] = [
            'Project' => '',
            'Plot No' => '',
            'Status' => '',
            'Marketer Name' => '',
            'Plot Value' => 'Total Amount Paid: ' . $totalAmountPaid,
            'Total Sqft' => '',
            'Amount Paid' => '',
        ];

        return collect($modifiedData);
    }


    public function headings(): array
    {
        return [
            'Project',
            'Plot No',
            'Status',
            'Marketer Name',
            'Plot Value',
            'Total Sqft',
            'Amount Paid',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        return [
            $lastRow => ['font' => ['bold' => true]],
        ];
    }
    
}
