<?php

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



Route::get('/', 'HomeController@index')->name('home');

Route::get('post/{slug}', 'PostController@details')->name('post.details');
Route::get('posts', 'PostController@index')->name('posts.index');
Route::get('category/{slug}', 'PostController@postByCategory')->name('category.post');
Route::get('tag/{slug}', 'PostController@postByTag')->name('tag.post');

Route::get('profile/{username}', 'AuthorController@profile')->name('author.profile');

Route::post('subscriber', 'SubscriberController@store')->name('subscriber.store');

Route::get('search', 'SearchController@search')->name('search');

Auth::routes();

Route::group(['middleware'=>['auth']], function(){
    Route::post('favorite/{post}/add', 'FavoriteController@add')->name('post.favorite');
});


Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>['auth','admin']], function (){
    Route::get('/dashboard','DashbordController@index')->name('admin.dashboard');

    Route::get('settings', 'SettingsController@index')->name('admin.settings');
    Route::put('profile-update', 'SettingsController@updateProfile')->name('admin.profile.update');
    Route::put('password-update', 'SettingsController@updatePassword')->name('admin.password.update');

    Route::resource('tag', 'TagController');
    Route::resource('category', 'CategoryController');
    Route::resource('post', 'PostController');

    Route::get('pending/post', 'PostController@pending')->name('post.pending');
    Route::put('/post/{id}/approve', 'PostController@approve')->name('post.approve');

    Route::get('/subscriber', 'SubscriberController@index')->name('subscriber.index');
    Route::delete('/destroy/{subscriber}', 'SubscriberController@destroy')->name('subscriber.destroy');
});

Route::group(['prefix'=>'author', 'namespace'=>'Author', 'middleware'=>['auth','author']], function(){
    Route::get('/dashboard', 'DashbordController@index')->name('author.dashboard');

    // Route::resource('post', 'PostController');

    Route::get('settings', 'SettingsController@index')->name('author.settings');
    Route::put('profile-update', 'SettingsController@updateProfile')->name('author.profile.update');
    Route::put('password-update', 'SettingsController@updatePassword')->name('author.password.update');

});

View::composer('layouts.frontend.partial.footer', function ($view) {
   $categories = App\Category::all();
   $view->with('categories', $categories);
});
