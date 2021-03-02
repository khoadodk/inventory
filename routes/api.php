<?php

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::apiResource('/employee', 'Api\EmployeeController');
Route::apiResource('/supplier', 'Api\SupplierController');
Route::apiResource('/category', 'Api\CategoryController');
Route::apiResource('/product', 'Api\ProductController');
Route::apiResource('/expense', 'Api\ExpenseController');
Route::apiResource('/customer', 'Api\CustomerController');

// Salary Routes
Route::post('/salary/paid/{id}', 'Api\SalaryController@paid');
Route::get('/salary', 'Api\SalaryController@allSalary');
Route::get('/salary/view/{id}', 'Api\SalaryController@viewSalary');
Route::get('/salary/edit/{id}', 'Api\SalaryController@editSalary');
Route::post('/salary/update/{id}', 'Api\SalaryController@updateSalary');