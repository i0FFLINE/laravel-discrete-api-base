<?php

use Illuminate\Support\Facades\Route;

Route::/*middleware(['throttle:6,1'])->*/ prefix('auth')->group(function () {
    // authenticate
    Route::post('/', 'Guest\\AuthenticateController')->name('authenticate');
    // register
    Route::post('/register', 'Guest\\RegisterController')->name('register');
    // password reset
    Route::prefix('password')->group(function () {
        // request password reset link
        Route::put('/forgot', 'Guest\\PasswordForgotController')->name('password.request');
        // reset password
        Route::put('/reset', 'Guest\\PasswordResetController')->name('password.reset');
    });
});

//
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//
Route::middleware(['auth:sanctum'])->prefix('auth')->group(function () {
    // user
    Route::prefix('/user')->group(function () {
        // get user
        Route::get('/', 'Auth\\User\\UserController');
        // update user
        Route::put('/', 'Auth\\User\\UserUpdateController');
        // update user's two-factor option
        Route::put('/2fa', 'Auth\\User\\User2faController');
        // logout
        Route::post('/logout', 'Auth\\User\\LogoutController')->name('logout');
        // password confirmation
        Route::put('/confirm-password', 'Auth\\User\\UserConfirmPasswordController');
        // generate public name
        Route::middleware(['throttle:6,1'])->put('/generate-public-name', 'Auth\\User\\UserPublicNameController');
        // delete user
        if (config('discreteapibase.features.user_delete', false) === true) {
            Route::delete('/', 'Auth\\User\\UserDeleteController');
        }
        // verify email
        // do not forget to modify your User model to implement or not the MustVerifyEmail
        if (config('discreteapibase.features.email_verification', false) === true) {
            // email verification
            Route::prefix('email-verification')->group(function () {
                // request verification link
                Route::post('/notification', 'Auth\\VerifyEmail\\VerificationResendController')->name('verification.send');
                // verify email
                Route::middleware('signed')->get('/{id}/{hash}', 'Auth\\VerifyEmail\\VerificationController')->name('verification.verify');
            });
        }
        // change email sequence
        Route::middleware(['throttle:6,1', 'signed'])->get('/confirm-change-email', 'Auth\\User\\UserChangeEmailController')->name('user.change.email');
        // profile
        Route::prefix('/profile')->group(function () {
            // update
            Route::put('/', 'Auth\\Profile\\ProfileUpdateController');
            // avatars
            Route::prefix('/avatar')->group(function () {
                // get as image
                Route::get('/', 'Auth\\Profile\\ProfileAvatarController');
                // upload new image
                Route::post('/', 'Auth\\Profile\\ProfileAvatarUpdateController');
                // remove image
                Route::delete('/', 'Auth\\Profile\\ProfileAvatarDeleteController');
            });
        });
        // notifications
        Route::prefix('notifications')->group(function () {
            // get notifications
            Route::get('/', 'Auth\\NotificationAlerts\\NotificationAlertsController');
            // make notification read
            Route::put('/{id?}', 'Auth\\NotificationAlerts\\NotificationReadAlertsController');
        });
        // organizations
        if (config('discreteapibase.features.organizations', false) === true) {
            $names = config('discreteapibase.organization.plural_name');
            Route::prefix($names)->group(function () {
                // create organization
                Route::put('/', 'Auth\\Organizations\\OrganizationCreateController');
                // get my organization
                Route::get('/{id}', 'Auth\\Organizations\\OrganizationController');
                // update my organization
                Route::put('/{id}', 'Auth\\Organizations\\OrganizationUpdateController');
                // delete my organization
                Route::delete('/{id}', 'Auth\\Organizations\\OrganizationDeleteController');
                // list my organizations
                Route::get('/', 'Auth\\Organizations\\OrganizationsController');
                // switch organization
                Route::put('/switch/{id}', 'Auth\\Organizations\\OrganizationSwitchController');
            });
        }
    });
});
