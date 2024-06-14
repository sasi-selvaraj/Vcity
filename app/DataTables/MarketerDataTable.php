<?php

namespace App\DataTables;

use App\Models\Marketer;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MarketerDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($data) {
                return $this->getActionColumn($data);
            })
            ->addColumn('created_at', function ($data) {
                return date_format($data->created_at, 'd-m-Y');
            })
            ->addColumn('director_name', function ($data) {
                if (!empty($data->crm) && $data->crm != null) {
                    $marketer = Marketer::where('id', $data->crm)->first();
                    $marketer_name = $marketer->name;
                } elseif (!empty($data->assistant_director) && $data->assistant_director != null) {
                    $marketer = Marketer::where('id', $data->assistant_director)->first();
                    $marketer_name = $marketer->name;
                } elseif (!empty($data->director) && $data->director != null) {
                    $marketer = Marketer::where('id', $data->director)->first();
                    $marketer_name = $marketer->name;
                } elseif (!empty($data->senior_director) && $data->senior_director != null) {
                    $marketer = Marketer::where('id', $data->senior_director)->first();
                    $marketer_name = $marketer->name;
                } elseif (!empty($data->chief_director) && $data->chief_director != null) {
                    $marketer = Marketer::where('id', $data->chief_director)->first();
                    $marketer_name = $marketer->name;
                } else {
                    $marketer_name = '';
                }
                return $marketer_name;
            })
            ->addColumn('last_payment', function ($data) {
                $payment = Payment::where('payment_status', config('constants.payment_status.full_payment'))->where('marketer_id', $data->id)->orderBy("payment_date", "DESC")->first();
                if (empty($payment)) {
                    $data->days_ago = 'N/A';
                } else {
                    $paymentDate = Carbon::parse($payment->payment_date);
                    $currentDate = Carbon::now();
                    $yearsDifference = $currentDate->diffInYears($paymentDate);
                    $monthsDifference = $currentDate->diffInMonths($paymentDate);
                    $daysDifference = $currentDate->diffInDays($paymentDate);
                    if ($yearsDifference > 0) {
                        $data->days_ago = $yearsDifference == 1 ? $yearsDifference . ' year ago' : $yearsDifference . ' years ago';
                    } elseif ($monthsDifference > 0) {
                        $data->days_ago = $monthsDifference == 1 ? $monthsDifference . ' month ago' : $monthsDifference . ' months ago';
                    } elseif ($daysDifference > 0) {
                        $data->days_ago = $daysDifference == 1 ?  $daysDifference . ' day ago' : $daysDifference . ' days ago';
                    } else {
                        $data->days_ago = 'N/A';
                    }
                }
                return $data->days_ago;
            })
            ->editColumn('status', function ($data) {
                $ActiveStatus = $data->status ? 'Active' : 'Inactive';
                $ActiveClass = $data->status ? 'success' : 'danger';
                return "<a href='javascript:void(0);'><button class='btn btn-sm btn-$ActiveClass verify_status w-100' data-status='$data->status' data-director_id='$data->id'>$ActiveStatus</button></a>";
            })
            ->escapeColumns([])
            ->setRowId('id');
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(Marketer $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('marketer-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<"row"<"col-12"B><"col-6"l><"col-6"f>>rt<"row"<"col-4"i><"col-8 justify-content-end d-flex"p>>')
            ->orderBy(2)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('marketer_vcity_id')->title('Marketer No')->width(60),
            Column::make('name')->title('Marketer Name')->width(60),
            Column::make('designation')->width(60),
            Column::make('status')->width(60),
            Column::make('email')->title('email_id')->width(60),


            Column::make('father_name')->visible(false),
            Column::make('qualification')->title('Educational Qualification')->visible(false),
            Column::make('dob')->title('date_of_birth')->visible(false),
            Column::make('address')->visible(false),
            Column::make('acc_no')->title('Bank Account No')->visible(false),
            Column::make('ifsc_code')->title('ifsc')->visible(false),
            Column::make('branch')->visible(false),
            Column::make('city')->visible(false),
            Column::make('pincode')->visible(false),
            Column::make('mobile_no')->title('Mobile Phone')->visible(false),
            Column::make('aadhar_no')->visible(false),
            Column::make('pan_no')->visible(false),
            Column::make('director_name')->title('name')->visible(false),

            Column::make('last_payment')->width(60),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(30)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Marketer_' . date('YmdHis');
    }

    protected function getActionColumn($data): string
    {
        return "<div class='btn-group'>" . "<a href='" . url('marketers/' . $data->id) . "' class='text-success btn-sm mb-1 p-0'><i title='view' class='fa fa-eye'></i></a>" .
            "<a href='" . url('marketers/' . $data->id . '/edit/') . "' class='text-warning btn-sm mx-4 mb-1 p-0'><i title='Edit' class='fa fa-edit'></i></a>" .
            "<a href='javascript:void(0)' class='text-danger btn-sm p-0' onclick='deleteData($data->id)'><i title='Delete' class='fa fa-trash'></i></a>" . "</div>";
    }
}
