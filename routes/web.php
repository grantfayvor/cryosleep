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

Route::group(['prefix' => 'api', 'middleware' => 'auth'], function () {

    Route::get('/crypto', 'CryptoAccountController@getAll');
    Route::post('/crypto', 'CryptoAccountController@create');
    Route::get('/crypto/{id}', 'CryptoAccountController@getById');
    Route::delete('/crypto/{id}', 'CryptoAccountController@delete');
    Route::get('/crypto/address/user', 'CryptoAccountController@getUserAddress');
    Route::get('/crypto/address/confirm', 'CryptoAccountController@confirmAddress');

    Route::get('/transaction', 'TransactionController@getAll');
    Route::post('/transaction', 'TransactionController@create');
    Route::get('/transaction/{id}', 'TransactionController@getById');
    Route::delete('/transaction/{id}', 'TransactionController@delete');
    Route::post('/transaction/payment/generate_url', 'TransactionController@generatePaymentURL');
    Route::post('/transaction/withdrawal/generate_url', 'TransactionController@generateWithdrawalURL');
    Route::get('/transaction/find/user', 'TransactionController@getUsersTransactions');
    Route::get('/transaction/user/confirmed', 'TransactionController@getUserConfirmedTransactions');

    Route::post('/withdrawal', 'WithdrawalInfoController@create')->name('withdrawalinfo.save');
    Route::get('/withdrawal', 'WithdrawalInfoController@getAll');
    Route::get('/withdrawal/unapproved', 'WithdrawalInfoController@getUnapprovedWithdrawals');
    Route::put('/withdrawal/approve/{id}', 'WithdrawalInfoController@approveWithdrawalRequest')->name('withdrawal.approve');

    Route::get('/transaction_plan', 'TransactionPlanController@getAll');
    Route::post('/transaction_plan', 'TransactionPlanController@create');
    Route::get('/transaction_plan/{id}', 'TransactionPlanController@getById');
    Route::delete('/transaction_plan/{id}', 'TransactionPlanController@delete');

    Route::get('/transaction_type', 'TransactionTypeController@getAll');
    Route::post('/transaction_type', 'TransactionTypeController@create');
    Route::get('/transaction_type/{id}', 'TransactionTypeController@getById');
    Route::delete('/transaction_type/{id}', 'TransactionTypeController@delete');

    Route::get('/user/transactions', 'UserController@getTransactionsInformation');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
