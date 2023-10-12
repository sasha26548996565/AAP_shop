<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    logger()
        ->channel('telegram')
        ->debug('some message//.');
    return view('welcome');
});
