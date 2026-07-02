<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('page_test');
});

Route::get('/ui/test', function () {
    return view('ui.test');
});

Route::get('/layout/test', function () {
    return view('layout.test');
});

Route::get('/forms/test', function () {
    return view('forms.test');
});
