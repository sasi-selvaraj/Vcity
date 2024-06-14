<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plots PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 100%;
        }

        .bg-primary {
            background-color: #fb6340 !important;
        }

        .bg-success {
            background-color: #2dce89;
        }

        .bg-info {
            background-color: #1171ef !important;
        }

        .bg-warning {
            background-color: #fb6340;
        }

        .bg-danger {
            background-color: #f5365c;
        }

        .bg-green {
            background-color: #83bd2d;
        }


        .plot-table {
            width: 100%;
            border-collapse: collapse;
        }

        .plot-table td {
            width: 25%;
            height: 15%;
            padding: 10px;
            margin: 20px;
            box-sizing: border-box;
        }

        .plot-card {
            width: 80%;
            height: 12%;
            padding: 10px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            text-align: center;
            color: black;
            font-weight: 600;
        }

        .plot-details {
            padding: 10px;
        }

        .plot-details h4 {
            margin-bottom: 1px;
            font-size: 18px;
        }

        .plot-details h5 {
            margin-top: 1px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    @if ($data->isNotEmpty())
        <h1>{{ $data->first()->project->project_name }}</h1>
        <table class="plot-table">
            <tr>
                @foreach ($data as $index => $plot)
                    <td>
                        <div
                            class="plot-card
                                    @if ($plot->status == 'Available') bg-success
                                    @elseif ($plot->status == 'Hold') bg-danger
                                    @elseif ($plot->status == 'Temporary Booking') bg-green
                                    @elseif ($plot->status == 'Booking') bg-primary
                                    @elseif ($plot->status == 'Full Payment') bg-warning
                                    @elseif ($plot->status == 'Registered') bg-info @endif">
                            <div class="plot-details">
                                <h4>{{ $plot->plot_no }}</h4><br>
                                <h5>{{ $plot->status }}</h5>
                            </div>
                        </div>
                    </td>
                    @if (($index + 1) % 4 == 0 && !$loop->last)
            </tr>
            <tr>
    @endif
    @endforeach
    </tr>
    </table>
    @endif

</html>
