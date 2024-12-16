$(document).ready(function() {
    localStorage.removeItem('db_name');
    let token = "{{Cache::get('api_token')}}";
    let db_name = "{{Session::get('db_name')}}";
    let User = "{{Cache::get('user')}}";
    localStorage.setItem('User', User);
    localStorage.setItem('token', token);
    localStorage.setItem('db_name', db_name);
})