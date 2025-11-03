<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\navBar;

Route::get('/', [navBar::class, 'getNavbar']);
