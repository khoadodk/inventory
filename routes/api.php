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

// POS Routes
Route::get('/productsbycat/{id}', 'Api\PosController@getProductsByCat');
Route::post('/posorder', 'Api\PosController@posOrder');
Route::get('/today/sell', 'Api\PosController@todaySell');
Route::get('/today/income', 'Api\PosController@todayIncome');
Route::get('/today/due', 'Api\PosController@todayDue');
Route::get('/today/expense', 'Api\PosController@todayExpense');
Route::get('/today/stock', 'Api\PosController@todayStock');

// Cart Routes
Route::get('/addtocart/{id}', 'Api\CartController@addToCart');
Route::get('/cart', 'Api\CartController@getCart');
Route::get('/cart/remove/{id}', 'Api\CartController@removeItemFromCart');
Route::get('/cart/increment/{id}', 'Api\CartController@increment');
Route::get('/cart/decrement/{id}', 'Api\CartController@decrement');
Route::get('/tax', 'Api\CartController@tax');

// Order Routes
Route::get('/order/today', 'Api\OrderController@getTodayOrders');
Route::get('/order/{id}', 'Api\OrderController@getOrderDetail');
Route::get('/order/detail/{id}', 'Api\OrderController@getOrderProducts');
Route::post('/order/search', 'Api\OrderController@getOrderByDate');

