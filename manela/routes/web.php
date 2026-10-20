<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('manela.home');
})->name('manela.home');

Route::view('/produtos', 'manela.produtos')->name('manela.produtos');
Route::view('/sobre-nos', 'manela.sobre-nos')->name('manela.sobre-nos');
Route::view('/encomendas', 'manela.encomendas')->name('manela.encomendas');
Route::view('/contato', 'manela.contato')->name('manela.contato');
Route::view('/login-dashboard', 'manela.login')->name('manela.login');
Route::view('/cadastro_usuario', 'manela.cadastro-user')->name('manela.cadastro_usuario');
Route::view('/dashboard', 'manela.dashboard')->name('manela.dashboard');
Route::view('/carrinho', 'manela.carrinho')->name('manela.carrinho');
