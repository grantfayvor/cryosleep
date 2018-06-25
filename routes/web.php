<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Auth::routes();

//Route::get('/home', function () {
//    return redirect('/');
//})->name('home')->middleware('auth');

Route::post('/api/transaction/ipn_webhook', 'TransactionController@ipn_webhook');

Route::group(['prefix' => 'api', 'middleware' => 'auth'], function () {

    Route::get('/crypto', 'CryptoAccountController@getAll')->middleware('admin');
    Route::post('/crypto', 'CryptoAccountController@create');
    Route::get('/crypto/{id}', 'CryptoAccountController@getById');
    Route::delete('/crypto/{id}', 'CryptoAccountController@delete')->middleware('admin');
    Route::get('/crypto/address/user', 'CryptoAccountController@getUserAddress');
    Route::get('/crypto/address/confirm', 'CryptoAccountController@confirmAddress');

    Route::get('/transaction', 'TransactionController@getAll')->middleware('admin');
    Route::post('/transaction', 'TransactionController@create');
    Route::get('/transaction/{id}', 'TransactionController@getById');
    Route::delete('/transaction/{id}', 'TransactionController@delete')->middleware('admin');
    Route::post('/transaction/payment/generate_url', 'TransactionController@generatePaymentURL');
    Route::post('/transaction/withdrawal/generate_url', 'TransactionController@generateWithdrawalURL');
    Route::get('/transaction/find/user', 'TransactionController@getUsersTransactions');
    Route::get('/transaction/user/confirmed', 'TransactionController@getUserConfirmedTransactions');
    Route::get('/transaction/coinpayment/all', 'TransactionController@getAllCoinTransactions');

    Route::post('/withdrawal', 'WithdrawalInfoController@create')->name('withdrawalinfo.save');
    Route::get('/withdrawal', 'WithdrawalInfoController@getAll')->middleware('admin');
    Route::get('/withdrawal/unapproved', 'WithdrawalInfoController@getUnapprovedWithdrawals')->middleware('admin');
    Route::put('/withdrawal/approve/{id}', 'WithdrawalInfoController@approveWithdrawalRequest')->name('withdrawal.approve')->middleware('admin');

    Route::get('/transaction_plan', 'TransactionPlanController@getAll');
    Route::post('/transaction_plan', 'TransactionPlanController@create')->middleware('admin');
    Route::get('/transaction_plan/{id}', 'TransactionPlanController@getById');
    Route::delete('/transaction_plan/{id}', 'TransactionPlanController@delete')->middleware('admin');

    Route::get('/transaction_type', 'TransactionTypeController@getAll');
    Route::post('/transaction_type', 'TransactionTypeController@create')->middleware('admin');
    Route::get('/transaction_type/{id}', 'TransactionTypeController@getById');
    Route::delete('/transaction_type/{id}', 'TransactionTypeController@delete')->middleware('admin');

    Route::get('/user/transactions', 'UserController@getTransactionsInformation');
    Route::get('/user', 'UserController@getAll')->middleware('admin');
    Route::delete('/user/{id}', 'UserController@delete')->middleware('admin');
    Route::get('/user/roles/{userId}', 'UserController@getUserRoles')->middleware('admin');
    Route::put('/user/update', 'UserController@update')->middleware('admin');

    Route::get('/role-with-claims', 'RolesAndClaimsController@getAllRoles')->middleware('admin');
    Route::post('/role-with-claims/create', 'RolesAndClaimsController@create')->middleware('admin');
    Route::put('/role-with-claims/assign', 'RolesAndClaimsController@assignRole')->middleware('admin');
    Route::delete('/role-with-claims/retract-user-role', 'RolesAndClaimsController@retractUserRole')->middleware('admin');
    Route::delete('/role-with-claims/retract-user-claims', 'RolesAndClaimsController@retractUserClaims')->middleware('admin');
    Route::delete('/role-with-claims/retract-role-claims', 'RolesAndClaimsController@retractRoleClaims')->middleware('admin');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
