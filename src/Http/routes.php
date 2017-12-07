<?php
Route::get('doc', 'DocController@index');
Route::get('doc/search', 'DocController@search');
Route::get('doc/list', "DocController@getList");
Route::get('doc/info', "DocController@getInfo");
Route::any('doc/debug', "DocController@debug");