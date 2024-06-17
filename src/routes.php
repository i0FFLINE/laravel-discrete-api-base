<?php

use Illuminate\Support\Facades\Route;

Route::/*middleware(['throttle:6,1'])->*/prefix('auth')->group(function () {

    // authenticate
    Route::post('/', 'AuthenticateController')->name('authenticate');

    // register
    Route::post('/register', 'RegisterController')->name('register');

    // password reset
    Route::prefix('password')->group(function () {

        // request password reset link
        Route::put('/forgot', 'PasswordForgotController')->name('password.request');

        // reset password
        Route::put('/reset', 'PasswordResetController')->name('password.reset');
    });

});

//
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//
Route::middleware(['auth:sanctum'])->prefix('auth')->group(function () {

    // user
    Route::prefix('/user')->group(function () {

        // get user
        Route::get('/', 'UserController');

        // update user
        Route::put('/', 'UserUpdateController');

        // logout
        Route::post('/logout', 'LogoutController')->name('logout');

        // password confirmation
        Route::put('/confirm-password', 'UserConfirmPasswordController');

        // delete user
        if (config('discreteapibase.account.features.user_delete', false) === true) {
            Route::delete('/', 'UserDeleteController');
        }

        // verify email
        // do not forget to modify your User model to implement or not the MustVerifyEmail
        if (config('discreteapibase.account.features.email_verification', false) === true) {
            // email verification
            Route::prefix('email-verification')->group(function () {
                // request verification link
                Route::post('/notification', 'VerificationResendController')->name('verification.send');
                // verify email
                Route::middleware('signed')->get('/{id}/{hash}', 'VerificationController')->name('verification.verify');
            });
        }

        // change email sequence
        Route::middleware(['throttle:6,1', 'signed'])->get('/confirm-change-email', 'UserChangeEmailController')->name('user.change.email');

        // profile
        Route::prefix('/profile')->group(function () {

            // update
            Route::put('/', 'ProfileUpdateController');

            // avatars
            Route::prefix('/avatar')->group(function () {

                // get as image
                Route::get('/', 'ProfileAvatarController');

                // upload new image
                Route::post('/', 'ProfileAvatarUpdateController');

                // remove image
                Route::delete('/', 'ProfileAvatarDeleteController');
            });
        });

        // notifications
        Route::prefix('notifications')->group(function () {

            // get notifications
            Route::get('/', 'NotificationAlertsController');

            // make notification read
            Route::put('/{notification?}', 'NotificationReadAlertsController');
        });
    });
});
