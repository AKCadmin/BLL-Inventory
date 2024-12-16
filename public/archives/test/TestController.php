<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class TestController extends Controller
{
    public function Test()
    {
        // Get the app timezone from the configuration
        $appTimezone = Config::get('app.timezone');

        // Get the current time in the configured timezone
        $currentTime = Carbon::now($appTimezone);

        // Format the time as a string
        $formattedTime = $currentTime->format('d-m-y H:i:s');

        // Pass the formatted time to the view
        return view('test', ['currentTime' => $formattedTime]);
    }
}
