<?php

use App\User;
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;

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
    Debugbar::startMeasure('getUser', 'Time for getting user');
    $users = User::all();
    Debugbar::stopMeasure('getUser');
    // Debugbar::error($user);

    return view('welcome');
});

Route::get('/exception', function () {
    try {
        throw new Exception('foobar');
    } catch (Exception $e) {
        Debugbar::addThrowable($e);
    }

    return view('welcome');
});

Route::get('/users', function () {
    $users = User::where('id', '<', 200)->get();
    $foo = 'bar';

    return view('users', [
        'users' => $users,
        'foo' => 'bar',
    ]);
});

Route::get('/posts', 'PostController@index')->name('post.index')->middleware('can:viewAny,App\Post');
// ->middleware('can:viewAny,App\Post');

Route::get('/posts/create', 'PostController@create')->name('post.create');
Route::post('/posts', 'PostController@store')->name('post.store');

Route::get('/mail', function () {
    $user = User::find(1);

    Mail::to($user)->send(new OrderShipped);

    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
