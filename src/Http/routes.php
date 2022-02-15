<?php
Route::group([
    'namespace' => 'RecursiveTree\Seat\InfoPlugin\Http\Controllers',
    'middleware' => ['web', 'auth'],
    'prefix' => 'info'
], function () {

    Route::get('/home', [
        'as'   => 'info.home',
        'uses' => 'InfoController@getHomeView',
        'middleware' => 'can:info.view_article'
    ]);

    Route::get('/article/create', [
        'as'   => 'info.create',
        'uses' => 'InfoController@getCreateView',
        'middleware' => 'can:info.edit_article'
    ]);

    Route::post('/article/save', [
        'as'   => 'info.save',
        'uses' => 'InfoController@getSaveInterface',
        'middleware' => 'can:info.edit_article'
    ]);

    Route::get('/article/edit/{id}', [
        'as'   => 'info.edit_article',
        'uses' => 'InfoController@getEditView',
        'middleware' => 'can:info.edit_article'
    ]);

    Route::get('/article/list', [
        'as'   => 'info.list',
        'uses' => 'InfoController@getListView',
        'middleware' => 'can:info.view_article'
    ]);

    Route::get('/manage', [
        'as'   => 'info.manage',
        'uses' => 'InfoController@getManageView',
        'middleware' => 'can:info.edit_article'
    ]);

    Route::get('/article/view/{id}', [
        'as'   => 'info.view',
        'uses' => 'InfoController@getArticleView',
        'middleware' => 'can:info.view_article'
    ]);

    Route::post('/upload_resource', [
        'as'   => 'info.upload_resource',
        'uses' => 'InfoController@uploadResource',
        'middleware' => 'can:info.edit_article'
    ]);

    Route::get('/resource/{id}', [
        'as'   => 'info.view_resource',
        'uses' => 'InfoController@viewResource',
        'middleware' => 'can:info.view_article'
    ]);

    Route::post('/deleteResource', [
        'as'   => 'info.delete_resource',
        'uses' => 'InfoController@deleteResource',
        'middleware' => 'can:info.view_article'
    ]);

    Route::post('/article/manage/set_home_article', [
        'as'   => 'info.set_home_article',
        'uses' => 'InfoController@setHomeArticle',
        'middleware' => 'can:info.edit_article'
    ]);

    Route::post('/article/manage/delete', [
        'as'   => 'info.delete_article',
        'uses' => 'InfoController@deleteArticle',
        'middleware' => 'can:info.edit_article'
    ]);

    Route::post('/article/manage/unset_home_article', [
        'as'   => 'info.unset_home_article',
        'uses' => 'InfoController@unsetHomeArticle',
        'middleware' => 'can:info.edit_article'
    ]);

    Route::post('/article/manage/set_public', [
        'as'   => 'info.set_article_public',
        'uses' => 'InfoController@setArticlePublic',
        'middleware' => 'can:info.edit_article'
    ]);

    Route::post('/article/manage/set_private', [
        'as'   => 'info.set_article_private',
        'uses' => 'InfoController@setArticlePrivate',
        'middleware' => 'can:info.edit_article'
    ]);

    Route::get('/about', [
        'as'   => 'info.about',
        'uses' => 'InfoController@about',
    ]);
});