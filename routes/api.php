<?php

//Auth Routes
Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function (){

    Route::post('signin', 'SignInController');

    Route::group(['middleware' => 'auth:api'], function(){

        Route::get('signout', 'SignOutController');

    });

    Route::get('me', 'MeController');

});

Route::group(['middleware' => 'auth:api', 'namespace' => 'Api'], function (){

    Route::get('/products', 'ProductsController@index');
    Route::post('/products/store', 'ProductsController@store');
    Route::post('/products/update/{id}', 'ProductsController@update');
    Route::delete('/products/destroy/{id}', 'ProductsController@destroy');
    Route::post('/products/delete_image/{id}', 'ProductsController@deleteImage');
});