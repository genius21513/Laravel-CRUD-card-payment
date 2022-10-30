<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\EditController;
use App\Http\Controllers\Card\CardController;
use App\Http\Controllers\Loan\LoanController;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\UsersController;

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
    return view('home');
});

Auth::routes();


Route::get('/',[AccountController::class, 'index'])->name('dashboard')->middleware('auth');

Route::prefix('user')->middleware('auth')->group(function(){
    Route::get('/me',[EditController::class, 'index'])->name('edit.user');
});

Route::resource('users', UsersController::class)->middleware('auth');

Route::prefix('account')->middleware('auth')->group(function(){
    Route::get('/',[AccountController::class, 'index'])->name('account.home');
    Route::get('/create',[AccountController::class, 'create'])->name('account.create');
    Route::post('/',[AccountController::class, 'store'])->name('account.store');
    Route::get('/delete/{id}',[AccountController::class, 'delete'])->name('account.delete');
});

Route::prefix('card')->middleware('auth')->group(function(){
    Route::post('/store',[CardController::class, 'store'])->name('card.store');
    Route::post('/ownTransfer',[CardController::class, 'ownTransfer'])->name('card.own.transfer');
    Route::post('/transfer',[CardController::class, 'transfer'])->name('card.transfer');
});

Route::prefix('loan')->middleware('auth')->group(function(){
    Route::post('/store', [LoanController::class, 'store'])->name('loan.store');
    Route::post('/repayment', [LoanController::class, 'repayment'])->name('loan.repayment');
});

Route::prefix('transaction')->middleware('auth')->group(function(){
    Route::get('/get-bank', [TransactionController::class, 'getBankName'])->name('transaction.get.bank.name');
});





