<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reminder</title>
</head>

<body>
    <h4>Dear {{ $payment->customer_name }},</h4>
    <p>This mail is to remind you that your plot no: {{ $payment->plots->plot_no }} has been booked for the date
        {{ $payment->payment_date }}.</p>
    <p>It's been {{ $daysDifference }} days since you made a payment.</p>
</body>

</html>
