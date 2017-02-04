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

   Route::get('nsp', function(){
      return view('nsp.index');
   });

   ###########
   # Xiaolab #
   ###########
   Route::get('home', 'Xiaolab@home');
   Route::get('publications', 'Xiaolab@publications');
   Route::get('links', 'Xiaolab@links');
   Route::get('tools', 'Xiaolab@tools');

   ############
   # Download #
   ############
   Route::get('resources', 'Resources@index');
   Route::get('resources/3drna_opt_dca', 'Resources@_3drna_opt_dca');

   #######
   # DCA #
   #######
   Route::get('DCA', 'DCA@index');
   Route::post('DCA', 'DCA@submit');
   Route::get('DCA/monitor/{id}', 'DCA@monitor');
   Route::get('DCA/task/{id}', 'DCA@task');
   Route::get('DCA/result/{id}', 'DCA@result');
   Route::get('DCA/download/{id}/{type}', 'DCA@download');
   Route::get('DCA/running_tasks/{ip}', 'DCA@running_tasks');
   Route::post('DCA/query', 'DCA@query');

   #########
   # 3dDNA #
   #########
   Route::get('3dDNA', 'RNA3D@dna');
   Route::post('3dDNA/Submit', 'RNA3D@submit');
   Route::get('duplex_2d/{seq}', 'RNA3D@duplex_2d');
   Route::get('triplex_2d/{seq}', 'RNA3D@triplex_2d');
   Route::get('2dDNA', 'DNA2D@new_job');

   ##########
   # CADNAS #
   ##########
   Route::get('CADNAS', 'CADNAS@show');
   Route::post('CADNAS/submit', 'CADNAS@submit');

   #########
   # 3dRNA #
   #########
   Route::get('jian', 'RNA3D@jian');
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
   //Route::get('3dRNA/jobs', 'RNA3D@jobs');
   Route::get('3dRNA/monitor/{job}', 'RNA3D@monitor');
   Route::get('3dRNA/running_tasks/{ip}', 'RNA3D@running_tasks');
   Route::get('3dRNA/validate/{paper}/{set}/{item}/{test_type}', 'RNA3D@test');

   Route::post('3dRNA/query', 'RNA3D@query');
   Route::post('3dRNA/submit', 'RNA3D@submit');

   ##############
   # 3dRNAscore #
   ##############
   Route::get('3dRNAscore', 'RNAscore@index');
   Route::post('3dRNAscore/submit', 'RNAscore@submit');

   Route::get('lei', 'lei@show');

   #########
   # 3dRPC #
   #########
   Route::post('3dRPC/submit', 'RPC@submit');
   Route::get('3dRPC/running_tasks/{ip}', 'RPC@running_tasks');
   Route::get('3dRPC/result/{job}', 'RPC@result');
   Route::get('3dRPC', 'RPC@index');
   Route::get('3dRPC/monitor/{job}', 'RPC@monitor');
   Route::get('3dRPC/tasks/{job}', 'RPC@tasks');
   Route::get('3dRPC/view/{job}/{num}', 'RPC@view');
   Route::get('3dRPC/download/{job}/{num}', 'RPC@download');
   Route::post('3dRPC/query', 'RPC@query');

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

