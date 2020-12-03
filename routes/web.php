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
Auth::routes();

Route::group(['middleware' => ['tenant']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/product/{alias?}', 'HomeController@product')->name('product')->where('alias', '(.*)');
    Route::get('/admin-login/', 'Auth\LoginController@loginAdmin')->name('login.admin');
    Route::post('/doPostLogin', 'Auth\LoginController@doPostLogin')->name('postLogin.admin');
    Route::get('/error', 'HomeController@error')->name('error');

    Route::post('/register', 'HomeController@send_register')->name('register');
    Route::get('/hoan-thanh', 'HomeController@success')->name('success');

    Route::get('/bai-viet/{alias?}', 'HomeController@new_detail')->name('new_detail')->where('alias', '(.*)');

    Route::group(['middleware' => ['manage'],  'prefix' => 'admin'], function () {
        Route::get('/', 'AdminController@index')->name('index.admin');
    });
    Route::get('/{href?}', 'HomeController@category')->name('category')->where('alias', '(.*)');
});

Route::group(['middleware' => ['tenant', 'admin'],  'prefix' => 'admin'], function () {

    Route::get('/report', 'AdminController@monthlyReport')->name('report.admin');
    Route::get('/menu', 'AdminController@menu')->name('menu.admin');
    Route::post('/save_menu', 'AdminController@saveMenu')->name('save_menu.admin');
    Route::get('/product', 'AdminController@product_manage')->name('product.admin');
    Route::post('/delete-product', 'AdminController@delete_product')->name('delete_product.admin');
    Route::get('/product-item/{id?}', 'AdminController@product_item')->name('product_item.admin')->where('id', '(.*)');
    Route::post('/save_product', 'AdminController@save_product_item')->name('save_product.admin');
    Route::post('/upload-image', 'AdminController@store_image')->name('store_image.admin');
    Route::post('/delete-image', 'AdminController@delete_image')->name('delete_image.admin');
    Route::get('/change-pass', 'AdminController@change_password')->name('change_password.admin');
    Route::post('/change_pass', 'AdminController@confirm_change_pass')->name('confirm_change_pass.admin');
    Route::get('/expense', 'ExpenseController@expense')->name('expense.admin');
    Route::get('/create-expense', 'ExpenseController@create')->name('create_expense.admin');
    Route::post('/save-expense', 'ExpenseController@save_expense')->name('save_expense.admin');
    Route::get('/edit-expense/{id?}', 'ExpenseController@edit')->name('edit_expense.admin')->where('id', '(.*)');

    Route::get('/class', 'AdminController@class')->name('class.admin');
    Route::post('/save-class', 'AdminController@save_class')->name('save_class.admin');


    Route::get('/income', 'IncomeController@index')->name('income.admin');
    Route::get('/create-income', 'IncomeController@create')->name('create_income.admin');
    Route::post('/save-income', 'IncomeController@save_income')->name('save_income.admin');
    Route::post('/collect', 'IncomeController@collect_income')->name('collect_income.admin');
    Route::get('/edit-income/{id?}', 'IncomeController@edit')->name('edit_income.admin')->where('id', '(.*)');

    Route::get('/news', 'NewsController@index')->name('news.admin');
    Route::get('/add-news', 'NewsController@add')->name('add_news.admin');
    Route::post('/add-news', 'NewsController@save')->name('save_news.admin');
    Route::get('/news-content', 'NewsController@showContent')->name('news_content.admin');
    Route::delete('/delete', 'NewsController@delete_news')->name('delete_news.admin');

    Route::get('/edit-news/{id?}', 'NewsController@edit')->name('edit_news.admin');

    Route::post('/edit-news', 'NewsController@store')->name('store_news.admin');
});

Route::group(['middleware' => ['tenant', 'super_admin', 'admin'],  'prefix' => 'admin'], function () {
    Route::get('/config', 'AdminController@config')->name('config.admin');
    Route::post('/config', 'AdminController@save_config')->name('save_config.admin');
    Route::get('/group', 'UserGroupController@group')->name('group.admin');
    Route::post('/group', 'UserGroupController@save_group')->name('save_group.admin');
    Route::delete('/delete-group', 'UserGroupController@delete_group')->name('delete_group.admin');
    Route::get('/user', 'UserGroupController@user')->name('user.admin');
    Route::post('/save_user', 'UserGroupController@save_user')->name('save_user.admin');
    Route::post('/delete-expense/{id?}', 'ExpenseController@delete')->name('delete_expense.admin');
    Route::put('/refund-expense/{id?}', 'ExpenseController@refund')->name('refund_expense.admin')->where('id', '(.*)');
    Route::post('/delete-income/{id?}', 'IncomeController@delete')->name('delete_income.admin');
});

Route::group(['middleware' => ['tenant', 'order_permission'],  'prefix' => 'admin'], function () {
    // Home admin
    Route::get('/order', 'OrderController@index')->name('order.admin');
    Route::get('/order-detail', 'OrderController@detail')->name('order_detail.admin');
    Route::get('/create-order', 'OrderController@create')->name('create_order.admin');
    Route::post('/create-order', 'OrderController@save')->name('save_order.admin');
    Route::get('/edit-order/{id?}', 'OrderController@edit')->name('edit_order.admin')->where('id', '(.*)');
    Route::delete('/remove-order-item', 'OrderController@remove_item')->name('remove_order_item.admin');
    Route::post('/edit-order', 'OrderController@save_edit')->name('save_edit.admin');
    Route::put('/pay-order', 'OrderController@pay')->name('pay_order.admin');
    Route::delete('/delete-order', 'OrderController@delete')->name('delete_order.admin');

    Route::get('/create-order-new', 'OrderController@create_new')->name('create_order_new.admin');
});

