<?php

namespace App\DataTables;

use App\Models\Payment;
use App\Models\Project;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PaymentsDataTable extends DataTable
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
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d-m-Y');
            })
            ->editColumn('project_id', function ($data) {
                return $data->projects->project_name;
            })
            ->editColumn('plot_id', function ($data) {
                return $data->plots->plot_no;
            })
            ->editColumn('marketer_id', function ($data) {
                return $data->marketers->name;
            })
            ->editColumn('payment_status', function ($data) {
                if ($data->payment_status == 1) {
                    return 'Development Charges';
                } elseif ($data->payment_status == 2) {
                    return 'Plot Amount';
                } elseif ($data->payment_status == 3) {
                    return 'Full Payment';
                } elseif ($data->payment_status == 4) {
                    return 'Registered';
                } elseif ($data->payment_status == 5) {
                    return 'Cancelled';
                }
            })
            ->editColumn('payment_date', function ($data) {
                if ($data->payment_date !== null) {
                    $paymentDate = new DateTime($data->payment_date);
                    return $paymentDate->format('d-m-Y');
                } else {
                    return null;
                }
            })
            ->filterColumn('plot_id', function ($query, $keyword) {
                $query->whereHas('plots', function ($subquery) use ($keyword) {
                    $subquery->where('plot_no', 'LIKE', "%{$keyword}%");
                });
            })
            ->filterColumn('marketer_id', function ($query, $keyword) {
                $query->whereHas('marketers', function ($subquery) use ($keyword) {
                    $subquery->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Payment $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('payments-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<"row"<"col-12"B><"col-6"l><"col-6"f>>rt<"row"<"col-4"i><"col-8 justify-content-end d-flex"p>>')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
            ])
            ->parameters([
                'initComplete' => 'function () {
                    var table = this;

                    // Filter by Payment Status
                    table.api().columns([3]).every(function () {
                        var column = this;
                        var select = $("<select style=\"color: #758395;font-weight:700; font-size: 17px; border:2px solid #c3cce0; border-radius:5px; width: 300px;\"><option value=\"\">Filter by Payment Status</option></select>")
                            .appendTo($(column.header()).empty())
                            .on(\'change\', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? \'\\\\b\' + val + \'\\\\b\' : \'\', true, false).draw();
                            });
                        ' . $this->getStatusFilterOptions() . '
                    });

                    // Filter by Project
                    table.api().columns([0]).every(function () {
                        var column = this;
                        var select = $("<select style=\"color: #758395;font-weight:700; font-size: 17px; border:2px solid #c3cce0; border-radius:5px; width: 300px;\"><option value=\"\">Filter by Project</option></select>")
                            .appendTo($(column.header()).empty())
                            .on(\'change\', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? \'\\\\b\' + val + \'\\\\b\' : \'\', true, false).draw();
                            });
                        ' . $this->getProjectFilterOptions() . '
                    });
                }',
            ]);
    }

    /**
     * Get options for the project filter dropdown.
     */
    protected function getProjectFilterOptions(): string
    {
        $options = '';

        foreach ($this->getProjects() as $id => $name) {
            $options .= "select.append('<option value=\"$id\">$name</option>');";
        }

        return $options;
    }

    /**
     * Get projects for the filter dropdown.
     */
    protected function getProjects(): array
    {
        return Project::pluck('project_name', 'id')->toArray();
    }

    /**
     * Get options for the Payment filter dropdown.
     */
    protected function getStatusFilterOptions(): string
    {
        $options = '';

        foreach ($this->getStatus() as $id => $payment_status) {
            $options .= "select.append('<option value=\"$id\">$payment_status</option>');";
        }

        return $options;
    }

    /**
     * Get payments for the filter dropdown.
     */
    protected function getStatus(): array
    {
        return [
            1 => 'Development Charges',
            2 => 'Plot Amount',
            3 => 'Full Payment',
            4 => 'Registered',
            5 => 'Cancelled',
        ];
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('project_id')->title('Project Name'),
            Column::make('plot_id')->title('Plot No'),
            Column::make('marketer_id')->title('Marketer Name'),
            Column::make('payment_status'),
            Column::make('payment_date'),
            Column::make('amount_paid'),
            Column::make('customer_name'),
            Column::make('father_or_husband_name')->visible(false),
            Column::make('mobile_no')->visible(false),
            Column::make('whatsapp_no')->visible(false),
            Column::make('address')->visible(false),
            Column::make('reference_id')->visible(false),
            Column::make('payment_details')->visible(false),
            Column::make('particulars')->visible(false),
            Column::make('payment_type')->visible(false),
            Column::make('cheque_number')->visible(false),
            Column::make('cheque_date')->visible(false),
            Column::make('bank')->visible(false),
            Column::make('branch')->visible(false),
            Column::make('ref_no')->visible(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Payments_' . date('YmdHis');
    }

    protected function getActionColumn($data): string
    {
        $iconPdf = '';
        $iconEdit = '';
        if ($data->payment_status == 5) {
            $iconEdit = "<a href='javascript:void(0)' class='text-warning btn-sm mb-1'><i title='Edit' class='fa fa-ban'></i></a>";
            $iconPdf = "<a href='javascript:void(0)' class='text-info btn-sm'><i title='PDF' class='fa fa-ban'></i></a>";
        }else {
            $iconEdit = "<a href='javascript:void(0)' class='text-warning btn-sm mb-1' onclick='editData($data->id)'><i title='Edit' class='fa fa-edit'></i></a>";
            $iconPdf = "<a href='" . url('/payments/download_receipt/' . $data->id . '/' . $data->payment_status) . "' class='text-info btn-sm' target='_blank'><i title='PDF' class='fa fa-file-pdf-o'></i></a>";
        }
        return "<div class='btn-group'>" .
            "<a href='javascript:void(0)' class='text-success btn-sm mb-1' onclick='viewData($data->id)'><i title='View' class='fa fa-eye'></i></a>" .
            $iconEdit.
            "<a href='javascript:void(0)' class='text-danger btn-sm' onclick='deleteData($data->id)'><i title='Delete' class='fa fa-trash'></i></a>" .
            $iconPdf .
            "</div>";
    }
}
