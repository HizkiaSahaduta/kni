<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('home');
});

Auth::routes([
    'register' => false,
    'reset' => false,
    'password' => false,
    'verify' => false,
  ]);

// Route::get('ChangeDefaultPassword', 'ChangeDefaultPasswordController@index');
// Route::get('ChangePass', 'ChangeDefaultPasswordController@ChangePass');


/*---------------------------------------------------------------------------------------------------------------------------------------------*/
//Route::get('/', 'HomeController@index');
Route::get('home', 'HomeController@index')->name('home');

/*---------------------------------------------------------------------------------------------------------------------------------------------*/
//-- PO Confirm
Route::get('POConfirm', 'POConfirmController@index')->name('POConfirm');
Route::post('getPOHdr', 'POConfirmController@getPOHdr');
Route::post('getSumHdr', 'POConfirmController@getSumHdr');
Route::post('getPODtl', 'POConfirmController@getPODtl');
Route::post('setApprove', 'POConfirmController@setApprove');
Route::post('setReject', 'POConfirmController@setReject');
Route::post('setUnApprove', 'POConfirmController@setUnApprove');

//-- DN Confirm
Route::get('DNConfirm', 'DNConfirmController@index')->name('DNConfirm');
Route::post('getDNHdr', 'DNConfirmController@getDNHdr');
Route::post('getDNDtl', 'DNConfirmController@getDNDtl');
Route::post('setDelivConfirm', 'DNConfirmController@setDelivConfirm');

//-- User Mgmt
Route::get('MyAccount', 'MyAccountController@index')->name('MyAccount');
Route::get('ChangePass', 'ChangePassController@index')->name('ChangePass');
Route::post('ActChangePass', 'ChangePassController@ActChangePass')->name('ActChangePass');





