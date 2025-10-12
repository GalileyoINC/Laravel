<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/login', fn () => view('app'))->name('login');

Route::get('/{any}', fn () => view('app'))->where('any', '.*');
