<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Full Payment</title>
    <style>
        *{
            padding: 0px;
            margin: 0px;
        }
        /* body {
            padding: 0px !important;
            margin: 0px !important;
            background-image: url('image/full-payment.jpeg');
            background-size:  100%;
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
    <span style="position:absolute;top:183px;left:225px">{{ $data->id ?? 'N/A' }}</span>
    <span style="position:absolute;top:182px;left:470px">{{ isset($data->payment_date) ? date('d-m-Y', strtotime($data->payment_date)) : 'N/A' }}</span>
    <span style="position:absolute;top:234px;left:240px">{{ strtoupper($data->customer_name) ?? 'N/A' }}</span>
    <span style="position:absolute;top:290px;left:290px">{{ strtoupper($data->father_or_husband_name) ?? 'N/A' }}</span>
    <span style="position:absolute;top:342px;left:150px">{{ $data->mobile_no ?? 'N/A' }}</span>
    <span style="position:absolute;top:342px;left:450px">{{ $data->whatsapp_no ?? 'N/A' }}</span>
    <span style="position:absolute;top:385px;left:185px;width:500px;">{{ $data->address ?? 'N/A' }}</span>
    <span style="position:absolute;top:502px;left:224px">{{ strtoupper($data->projects->project_name) ?? 'N/A' }}</span>
    <span style="position:absolute;top:551px;left:220px">{{ strtoupper($data->projects->project_location) ?? 'N/A' }}</span>
    <span style="position:absolute;top:551px;left:530px">{{ $data->plots->block ?? 'N/A' }}</span>
    <span style="position:absolute;top:597px;left:175px">{{ $data->plots->plot_no ?? 'N/A' }}</span>
    <span style="position:absolute;top:597px;left:335px">{{ $data->plots->facing ?? 'N/A' }}</span>
    <span style="position:absolute;top:597px;left:555px">{{ $data->plots->plot_sqft ?? 'N/A' }}</span>
    <span style="position:absolute;top:645px;left:220px">{{ $data->marketers->marketer_vcity_id ?? 'N/A' }}</span>
</body>
<body>
    <span style="position:absolute;top:183px;left:225px">{{ $data->id ?? 'N/A' }}</span>
    <span style="position:absolute;top:182px;left:470px">{{ isset($data->payment_date) ? date('d-m-Y', strtotime($data->payment_date)) : 'N/A' }}</span>
    <span style="position:absolute;top:234px;left:240px">{{ strtoupper($data->customer_name) ?? 'N/A' }}</span>
    <span style="position:absolute;top:290px;left:290px">{{ strtoupper($data->father_or_husband_name) ?? 'N/A' }}</span>
    <span style="position:absolute;top:342px;left:150px">{{ $data->mobile_no ?? 'N/A' }}</span>
    <span style="position:absolute;top:342px;left:450px">{{ $data->whatsapp_no ?? 'N/A' }}</span>
    <span style="position:absolute;top:385px;left:185px;width:500px;">{{ $data->address ?? 'N/A' }}</span>
    <span style="position:absolute;top:502px;left:224px">{{ strtoupper($data->projects->project_name) ?? 'N/A' }}</span>
    <span style="position:absolute;top:551px;left:220px">{{ strtoupper($data->projects->project_location) ?? 'N/A' }}</span>
    <span style="position:absolute;top:551px;left:530px">{{ $data->plots->block ?? 'N/A' }}</span>
    <span style="position:absolute;top:597px;left:175px">{{ $data->plots->plot_no ?? 'N/A' }}</span>
    <span style="position:absolute;top:597px;left:335px">{{ $data->plots->facing ?? 'N/A' }}</span>
    <span style="position:absolute;top:597px;left:555px">{{ $data->plots->plot_sqft ?? 'N/A' }}</span>
    <span style="position:absolute;top:645px;left:220px">{{ $data->marketers->marketer_vcity_id ?? 'N/A' }}</span>
</body>
</html>