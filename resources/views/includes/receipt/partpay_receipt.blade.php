<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Plot Amount</title>
    <style>
        *{
            padding: 0px;
            margin: 0px;
        }
        /* body {
            background-image: url('image/part-payment.jpeg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        } */
        span {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 95%;
        }
    </style>
</head>
<body>
    <span style="position:absolute;top:84px;left:195px;">{{ $data->id ?? 'N/A' }}</span>
    <span style="position:absolute;top:84px;left:467px;">{{ isset($data->payment_date) ? date('d-m-Y', strtotime($data->payment_date)) : 'N/A' }}</span>
    <span style="position:absolute;top:135px;left:233px;">{{ strtoupper($data->customer_name) ?? 'N/A' }}</span>
    <span style="position:absolute;top:191px;left:283px;">{{ strtoupper($data->father_or_husband_name) ?? 'N/A' }}</span>
    <span style="position:absolute;top:245px;left:130px;">{{ $data->mobile_no ?? 'N/A' }}</span>
    <span style="position:absolute;top:245px;left:450px;">{{ $data->whatsapp_no ?? 'N/A' }}</span>
    <span style="position:absolute;top:283px;left:180px;width:400px;">{{ $data->address ?? 'N/A' }}</span>
    <span style="position:absolute;top:375px;left:220px;">{{ strtoupper($data->projects->project_name) ?? 'N/A' }}</span>
    <span style="position:absolute;top:421px;left:210px;">{{ strtoupper($data->projects->project_location) ?? 'N/A' }}</span>
    <span style="position:absolute;top:421px;left:530px;">{{ $data->plots->block ?? 'N/A' }}</span>
    <span style="position:absolute;top:467px;left:160px;">{{ $data->plots->plot_no ?? 'N/A' }}</span>
    <span style="position:absolute;top:467px;left:330px;">{{ $data->plots->facing ?? 'N/A' }}</span>
    <span style="position:absolute;top:467px;left:560px;">{{ $data->plots->plot_sqft ?? 'N/A' }}</span>
    <span style="position:absolute;top:560px;left:110px;width:220px;">{{ $data->payment_details ?? 'N/A' }}</span>
    <span style="position:absolute;top:560px;left:415px;width:200px;">{{ $data->particulars ?? 'N/A' }}</span>
    <span style="position:absolute;top:560px;left:600px;">{{ str_replace('.00', ' /-', preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $data->amount_paid)) ?? 'N/A' }}</span>
    <span style="position:absolute;top:638px;left:130px;">{{ $data->amount_in_words ?? 'N/A' }}</span>
    <span style="position:absolute;top:638px;left:590px;">{{ str_replace('.00', ' /-', preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $data->amount_paid)) ?? 'N/A' }}</span>
    <span style="position:absolute;top:687px;left:210px;">{{ $data->marketers->marketer_vcity_id ?? 'N/A' }}</span>
</body>
<body>
    <span style="position:absolute;top:94px;left:195px;">{{ $data->id ?? 'N/A' }}</span>
    <span style="position:absolute;top:94px;left:467px;">{{ isset($data->payment_date) ? date('d-m-Y', strtotime($data->payment_date)) : 'N/A' }}</span>
    <span style="position:absolute;top:145px;left:233px;">{{ strtoupper($data->customer_name) ?? 'N/A' }}</span>
    <span style="position:absolute;top:199px;left:283px;">{{ strtoupper($data->father_or_husband_name) ?? 'N/A' }}</span>
    <span style="position:absolute;top:248px;left:130px;">{{ $data->mobile_no ?? 'N/A' }}</span>
    <span style="position:absolute;top:248px;left:450px;">{{ $data->whatsapp_no ?? 'N/A' }}</span>
    <span style="position:absolute;top:285px;left:180px;width:400px;">{{ $data->address ?? 'N/A' }}</span>
    <span style="position:absolute;top:377px;left:220px;">{{ strtoupper($data->projects->project_name) ?? 'N/A' }}</span>
    <span style="position:absolute;top:422px;left:210px;">{{ strtoupper($data->projects->project_location) ?? 'N/A' }}</span>
    <span style="position:absolute;top:422px;left:530px;">{{ $data->plots->block ?? 'N/A' }}</span>
    <span style="position:absolute;top:467px;left:160px;">{{ $data->plots->plot_no ?? 'N/A' }}</span>
    <span style="position:absolute;top:467px;left:330px;">{{ $data->plots->facing ?? 'N/A' }}</span>
    <span style="position:absolute;top:467px;left:560px;">{{ $data->plots->plot_sqft ?? 'N/A' }}</span>
    <span style="position:absolute;top:560px;left:110px;width:220px;">{{ $data->payment_details ?? 'N/A' }}</span>
    <span style="position:absolute;top:560px;left:415px;width:200px;">{{ $data->particulars ?? 'N/A' }}</span>
    <span style="position:absolute;top:560px;left:600px;">{{ str_replace('.00', ' /-', preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $data->amount_paid)) ?? 'N/A' }}</span>
    <span style="position:absolute;top:638px;left:130px;">{{ $data->amount_in_words ?? 'N/A' }}</span>
    <span style="position:absolute;top:638px;left:590px;">{{ str_replace('.00', ' /-', preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $data->amount_paid)) ?? 'N/A' }}</span>
    <span style="position:absolute;top:685px;left:210px;">{{ $data->marketers->marketer_vcity_id ?? 'N/A' }}</span>
</body>
</html>