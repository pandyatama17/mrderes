<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ConsoleController extends Controller
{
    public function clearViews()
    {
       Artisan::call('cache:clear');
       echo "Application cache cleared<br><br>";
       Artisan::call('view:clear');
       echo "Compiled views cleared!<br><br>";
       Artisan::call('view:cache');
       echo "Blade templates cached successfully!"; die();
    }

    public function migrate()
    {
        Artisan::call('migrate');
        return "php artisan migrate success!";
    }
}
