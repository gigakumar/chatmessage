<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Chat\ChatController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(UserController::class)->group(function () {
    Route::post('user/register', 'reg');                       // User Register   1.1
    Route::post('user/login', 'login');  
    Route::post('testing', 'testing'); 
    Route::post('insertmulti', 'insertmulti');       // Logout      
    
     // User Login      1.2    
});


Route::controller(ChatController::class)->group(function () {
    Route::post('reg', 'chatreg'); 
    Route::post('chatlogin', 'chatlogin'); 
    Route::post('showmsg', 'showmsg'); 
    Route::post('chatget', 'chatget');                   
           
});




Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->group(function () {
            Route::post('profile', 'profile');            // View Profile For User       1.3
            Route::post('editProfile', 'editProfile');   // Edit Profile For User        1.4
            Route::post('deleteProfile', 'deleteProfile');            // Delete Profile For User      1.5
            Route::post('changePassword', 'changePassword');   // Change Password For User     1.6 
            Route::post('logof', 'logout');      // Logout  
                      
        });

    });