<?php

use Common\Routing\Route;
use Presentation\Http\Controllers\Web\UserController;

Route::get('/', [UserController::class, 'index']);
Route::get('/about', [UserController::class, 'about']);
Route::get('/profile/{username}', [UserController::class, 'profile']);
Route::get('/contact', function () {
    echo 'Let\'s talk <a href="//tahazzot.me/contact">here</a>';
});
