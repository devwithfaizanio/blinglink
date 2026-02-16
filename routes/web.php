<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Dedoc\Scramble\Scramble;

Route::get('/', function () {
    return view('welcome');
});
Route::get('docs/api', function (){
    abort(404);
});

Scramble::registerUiRoute(path: 'docs/api/v1', api: 'v1');
Scramble::registerJsonSpecificationRoute(path: 'docs/v1.json', api: 'v1');


//clear cache
Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    return "Cache is cleared";
});
