<?php
declare(strict_types=1);
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


//$config = array_merge(config('translation-manager.route'), ['namespace' => 'Acmetemplate\TranslationManager', 'prefix' => 'translations']);
Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]], function() {
    Route::group(['prefix' => 'admin/translations'], function($router)
    {
        $router->get('Locale/Add', 'Controller@getAddLocale')->name('view.locale');
        $router->get('view/{groupKey?}', 'Controller@getView')->where('groupKey', '.*')->name('view.group');
        $router->get('/{groupKey?}', 'Controller@getIndex')->where('groupKey', '.*');
        $router->post('/add/{groupKey}', 'Controller@postAdd')->where('groupKey', '.*');
        $router->post('/edit/{groupKey}', 'Controller@postEdit')->where('groupKey', '.*');
        $router->post('/groups/add', 'Controller@postAddGroup');
        $router->post('/delete/{groupKey}/{translationKey}', 'Controller@postDelete')->where('groupKey', '.*');
        $router->post('/import', 'Controller@postImport');
        $router->post('/find', 'Controller@postFind');
        $router->post('/locales/add', 'Controller@postAddLocale');
        $router->post('/locales/remove', 'Controller@postRemoveLocale');
        $router->post('/publish/{groupKey}', 'Controller@postPublish')->where('groupKey', '.*');
        $router->post('/translate-missing', 'Controller@postTranslateMissing');
    });
});
