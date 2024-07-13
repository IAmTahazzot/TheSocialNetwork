<?php

// all api routes will be defined here
use Common\Routing\Route;
use Presentation\Http\Controllers\Web\UserController;

Route::post('/user', [UserController::class, 'createUser']);
