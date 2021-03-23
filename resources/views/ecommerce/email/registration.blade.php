<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Your Email Registration Verification</title>
</head>
<body>
    <h2>Hello, {{ $member['first_name'] }} {{ $member['last_name'] }}</h2>
    <br/>
    <p>Thank you for registered your account for <a href="{{ route('ecommerce.index') }}"><strong>E-Store</strong></a>.</p>
    <p>But, before you can use your account, you need to click <a href="{{ route('ecommerce.register.verify', $member['active_token']) }}"><strong>here</strong></a> to able login your account!
    <br/>
    <p>Thank you and enjoy your shopping only at <a href="{{ route('ecommerce.index') }}"><strong>E-Store</strong></a>.</p>
    <br/>
    <br/>
    <p>Best Regards, </p>
    <br/>
    <br/>
    <p>E-Store Management Account Team</p>
</body>
</html>
