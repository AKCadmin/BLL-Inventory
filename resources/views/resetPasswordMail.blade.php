<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{$data['title']}}</title>
</head>
<body>
    <h1>{{ config('app.name') }}</h1>

    <h3 style="color: rgb(0, 255, 242)">{{$data['body']}}</h3>
    <a href="{{$data['url']}}">Click here to reset password</a>
</body>
</html>