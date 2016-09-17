<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('lei', 'lei@show');
Route::get('lei/new', 'lei@show');

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('welcome', function () {
    return view('welcome');
});

Route::get('home', 'Xiaolab@home');

Route::get('publications', 'Xiaolab@publications');

#Route::get('2dRNA', 'RNA2D@new_job');

Route::get('DCA', 'DCA@index');
Route::post('DCA/msa', 'DCA@msa');
Route::post('DCA/dca', 'DCA@dca');
//Route::get('DCA/result/{id}', 'DCA@result');
//Route::get('DCA/download/{id}', 'DCA@download');

Route::get('3dDNA', 'RNA3D@dna');
Route::post('3dDNA/Submit', 'RNA3D@submit');
Route::get('duplex_2d/{seq}', 'RNA3D@duplex_2d');
Route::get('triplex_2d/{seq}', 'RNA3D@triplex_2d');
Route::get('2dDNA', 'DNA2D@new_job');

Route::get('CADNAS', 'CADNAS@show');
Route::post('CADNAS/submit', 'CADNAS@submit');

Route::post('test', function(){
    return 'hello';
});

    #########
    # 3dRNA #
    #########
    Route::get('3dRNA-1.0.html', 'RNA3D@assemble');
    Route::get('3dRNA-2.0.html', 'RNA3D@assemble');
    Route::get('3dRNA.html', 'RNA3D@assemble');
    Route::get('3dRNA', 'RNA3D@assemble');
    #Route::get('3dRNA', function(){return '<h3>We are upgrating the server...</h3>';});
    Route::get('3dRNA/test', 'RNA3D@assemble');
    Route::get('3dRNA/references', 'RNA3D@references');
    Route::get('3dRNA_DG', 'RNA3D@dg');
    Route::get('3dRNA/redo/{job}', 'RNA3D@redo');
    Route::get('3dRNA/result/{job}', 'RNA3D@result');
    Route::get('3dRNA/results/{job}', 'RNA3D@results');
    Route::get('3dRNA/tasks/{job}', 'RNA3D@tasks');
    Route::get('3dRNA/view/{job}/{num}', 'RNA3D@view');
    Route::get('3dRNA/download/{job}/{num}', 'RNA3D@download');
    Route::get('3dRNA/jobs', 'RNA3D@jobs');
    Route::get('3dRNA/monitor/{job}', 'RNA3D@monitor');

    Route::post('3dRNA/jobs', 'RNA3D@jobs');
    Route::post('3dRNA/submit', 'RNA3D@submit');

    ##############
    # 3dRNAscore #
    ##############
    Route::get('3dRNAscore', 'RNAscore@index');
    Route::post('3dRNAscore/submit', 'RNAscore@submit');


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
        //
});

