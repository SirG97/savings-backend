<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * @group Web URLs
 *
 * APIs that do not require User autherntication and is performed over a web browser
 */

Route::view('forgot/password', 'password.reset')->name('password.reset');
Route::post('reset/password', [\App\Http\Controllers\Auth\ResetPasswordController::class,
    'resetPasswordForm'])->name('password.update');

Route::group(['middleware' => ['web']], function () {
    /**
     * User Socialite Auth.
     *
     * This API opens a view that sets a <b>UUID</b> and stores it as a localStorage.
     *
     * Using the stored <b>UUID</b>, it subscribes the user to a unique public websocket channel
     * using laravel <b>Echo</b>, which will receive the <b>access_token</b> and <b>user_id</b>
     * as return payloads when the Oauth login is completed.
     *
     * It also provides a link to a <small class="badge badge-blue">auth/redirect/{uuid}</small> using the stored <b>UUID</b>
     * that starts the Oauth authentication process.
     *
     * @header Accept text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9
     * @header Content-Type text/html; charset=UTF-8
     *
     * @response 200 return view('sanctumauthstarter::socialite.auth')
     *
     * @group Web URLs
     */

    Route::get('auth/socialite', function() {
        if (env('APP_ENV') === 'production') {
            abort(404, trans('sanctumauthstarter::general.not_found'));
        }

        return view('sanctumauthstarter::socialite.auth',
            [ 'minutes' => config('sanctumauthstarter.cookie.minutes', 5) ]);
    })->name('socialite.auth');

    /**
     * User TwoFactor Auth.
     *
     * This API opens a view that receives a <b>UUID</b> from the URL and stores it as a localStorage.
     *
     * Using the <b>UUID</b>, it subscribes the user to a unique public websocket channel
     * using laravel <b>Echo</b>, which will receive the <b>access_token</b> and <b>user_id</b>
     * as return payloads when the twofactor login is completed.
     *
     * It also provides a link to a <small class="badge badge-blue">auth/twofactor/{email}/{uuid}</small> using <b>UUID</b>
     * that starts the twofactor authentication process.
     *
     * @header Accept text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9
     * @header Content-Type text/html; charset=UTF-8
     *
     * @response 200 return view('sanctumauthstarter::twofactor.auth')
     *
     * @group Web URLs
     */
    Route::get('auth/twofactor/{email}/{uuid}', function() {
        if (env('APP_ENV') === 'production') {
            abort(404, trans('sanctumauthstarter::general.not_found'));
        }

        return view('sanctumauthstarter::twofactor.auth');
    })->name('twofactor.auth');

    Route::get('auth/redirect/{uuid}', [\App\Http\Controllers\Auth\SocialiteController::class,
        'authRedirect'])->name('auth.redirect');
    Route::get('auth/callback', [\App\Http\Controllers\Auth\SocialiteController::class,
        'authCallback'])->name('auth.callback');

    Route::get('twofactor/required/{email}/{uuid}', [\App\Http\Controllers\Auth\LoginController::class,
        'twoFactorLogin'])->name('twofactor.required');
});
