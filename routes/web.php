<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/active/index', function () {
    return view('active.index');
})->name('index');

Route::get('/active/about', function () {
    return view('active.about');
})->name('about');

Route::get('/active/services', function () {
    return view('active.services');
})->name('services');

Route::get('/active/portfolio', function () {
    return view('active.portfolio');
})->name('portfolio');

Route::get('/active/team', function () {
    return view('active.team');
})->name('team');

Route::get('/active/blog', function () {
    return view('active.blog');
})->name('blog');

Route::get('/active/contact', function () {
    return view('active.contact');
})->name('contact');
