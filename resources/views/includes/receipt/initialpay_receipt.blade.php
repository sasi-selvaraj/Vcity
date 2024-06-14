<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Development Charges</title>
    <style>
        * {
            padding: 0px;
            margin: 0px;
        }
        /* body {
            background-image: url('image/init-payment.jpeg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        } */

        span {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 85%;
        }
    </style>
</head>

<body>
    <span style="position: absolute; top:361px; left:190px; font-size:17px;">{{ $data->id ?? 'N/A' }}</span>
    <span
        style="position: absolute; top:361px; left:520px; font-size:17px;">{{ isset($data->payment_date) ? date('d-m-Y', strtotime($data->payment_date)) : 'N/A' }}</span>
    <span
        style="position: absolute; top:427px; left:385px;"><strong>{{ strtoupper($data->customer_name ?? 'N/A') }}</strong></span>
    <span
        style="position: absolute; top:468px; left:310px; font-size:17px;"><strong>{{ str_replace('.00', ' /-', preg_replace('/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i', "$1,", $data->amount_paid)) ?? 'N/A' }}</strong></span>
    <span
        style="position: absolute; top:495px; left:165px;width:500px;line-height:3">{{ strtoupper($data->amount_in_words ?? 'N/A') }}</span>

    <span style="position: absolute; top:560px; left:498px;">{{ $data->mobile_no ?? 'N/A' }}</span>
    <span style="position: absolute; top:637px; left:155px;">{{ $data->bank ?? 'N/A' }}</span>
    <span style="position: absolute; top:637px; left:480px;">{{ $data->branch ?? 'N/A' }}</span>
    <span style="position: absolute; top:680px; left:170px;">{{ $data->ref_no ?? 'N/A' }}</span>
    <span
        style="position: absolute; top:680px; left:462px;">{{ isset($data->payment_date) ? date('d-m-Y', strtotime($data->payment_date)) : 'N/A' }}</span>
    <span style="position: absolute; top:761px; left:222px;"><strong>{{ $data->projects->project_name ?? 'N/A' }}</strong></span>
    <span style="position: absolute; top:799px; left:172px; font-size:17px;"><strong>{{ $data->plots->plot_no ?? 'N/A' }}</strong></span>
    <span style="position: absolute; top:799px; left:362px; font-size:17px;"><strong>{{ $data->plots->plot_sqft ?? 'N/A' }}</strong></span>
    <span style="position: absolute; top:799px; left:582px; font-size:17px;"><strong>{{ $data->plots->block ?? 'N/A' }}</strong></span>
    <span style="position: absolute; top:841px; left:204px;">{{ ($director != null && $director->name != null) ? $director->name : (($chief_director != null && $chief_director->name != null) ? $chief_director->name : 'N/A') }} - {{ $data->marketers->marketer_vcity_id ?? 'N/A' }}</span>
</body>


<body>
    <span style="position: absolute; top:368px; left:190px;">{{ $data->id ?? 'N/A' }}</span>
    <span
        style="position: absolute; top:368px; left:520px;">{{ isset($data->payment_date) ? date('d-m-Y', strtotime($data->payment_date)) : 'N/A' }}</span>
    <span
        style="position: absolute; top:427px; left:385px;"><strong>{{ strtoupper($data->customer_name ?? 'N/A') }}</strong></span>
    <span
        style="position: absolute; top:474px; left:310px;"><strong>{{ str_replace('.00', ' /-', preg_replace('/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i', "$1,", $data->amount_paid)) ?? 'N/A' }}</strong></span>
    <span
        style="position: absolute; top:501px; left:165px;width:500px;line-height:3">{{ strtoupper($data->amount_in_words ?? 'N/A') }}</span>

    <span style="position: absolute; top:560px; left:498px;">{{ $data->mobile_no ?? 'N/A' }}</span>
    <span style="position: absolute; top:639px; left:155px;">{{ $data->bank ?? 'N/A' }}</span>
    <span style="position: absolute; top:639px; left:480px;">{{ $data->branch ?? 'N/A' }}</span>
    <span style="position: absolute; top:682px; left:170px;">{{ $data->ref_no ?? 'N/A' }}</span>
    <span
        style="position: absolute; top:680px; left:462px;">{{ isset($data->payment_date) ? date('d-m-Y', strtotime($data->payment_date)) : 'N/A' }}</span>
    <span style="position: absolute; top:765px; left:222px;"><strong>{{ $data->projects->project_name ?? 'N/A' }}</strong></span>
    <span style="position: absolute; top:805px; left:172px;"><strong>{{ $data->plots->plot_no ?? 'N/A' }}</strong></span>
    <span style="position: absolute; top:805px; left:362px;"><strong>{{ $data->plots->plot_sqft ?? 'N/A' }}</strong></span>
    <span style="position: absolute; top:805px; left:582px;"><strong>{{ $data->plots->block ?? 'N/A' }}</strong></span>
    <span style="position: absolute; top:843px; left:204px;">{{ ($director != null && $director->name != null) ? $director->name : (($chief_director != null && $chief_director->name != null) ? $chief_director->name : 'N/A') }} - {{ $data->marketers->marketer_vcity_id ?? 'N/A' }}</span>
</body>

</html>
