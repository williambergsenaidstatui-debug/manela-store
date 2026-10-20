<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('manela.home');
})->name('san-doces.home');

Route::view('/produtos', 'san-doces.produtos')->name('san-doces.produtos');
Route::view('/sobre-nos', 'san-doces.sobre-nos')->name('san-doces.sobre-nos');
Route::view('/encomendas', 'san-doces.encomendas')->name('san-doces.encomendas');
Route::view('/contato', 'san-doces.contato')->name('san-doces.contato');
Route::view('/login-dashboard', 'san-doces.login')->name('san-doces.login');
Route::view('/cadastro_usuario', 'san-doces.cadastro-user')->name('san-doces.cadastro_usuario');
Route::view('/dashboard', 'san-doces.dashboard')->name('san-doces.dashboard');
Route::view('/carrinho', 'san-doces.carrinho')->name('san-doces.carrinho');
