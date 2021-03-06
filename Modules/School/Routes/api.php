<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('tes', 'DashboardParticipantController@tess');

Route::group(['prefix' => 'school'], function () {
    Route::post('login', 'SchoolController@login');
    Route::post('login/siswa', 'ParticipantController@login');
    Route::group(['middleware' => 'auth:school'], function () {
        Route::put('','ParticipantController@update');
        Route::group(['prefix' => 'group'], function () {
            Route::get('','GroupController@index');
        });
        Route::group(['prefix' => 'major'], function () {
            Route::get('','MajorController@index');
            Route::get('/showAll','MajorController@showAll');
        });
        Route::group(['prefix' => 'room'], function () {
            Route::get('','RoomController@index');
        });
        Route::group(['prefix' => 'master'], function () {
            Route::get('','MasterController@index');
            Route::post('','MasterController@store');
        });
        Route::group(['prefix' => 'subject'], function () {
            Route::get('','SubjectController@index');
            Route::put('/edit/{id}','SubjectController@update');
            Route::post('','SubjectController@store');
            Route::delete('/{id}','SubjectController@destroy');
        });
        Route::group(['prefix' => 'participant'], function () {
            Route::get('','ParticipantController@index');
            Route::post('/add','ParticipantController@store');
            Route::delete('/{id}/deleteAll', 'ParticipantController@deleteAllBysekolah');
            Route::delete('/{id}','ParticipantController@deleteParticipant');
            Route::put('/{id}','ParticipantController@update');
        });
        // Soal
        Route::group(['prefix' => 'soal'], function () {
            // Admin
            Route::post('','SoalController@store');
            Route::get('/show','SoalController@getSoal');
            Route::put('/{id}','SoalController@update');
            Route::delete('/{id}','SoalController@destroy');
            
        });
        
        Route::group(['prefix' => 'manage/tes'], function () {
            Route::get('', 'ManageTesController@index');
            Route::post('/mulai', 'ManageTesController@mulai');
            Route::post('/akhiri', 'ManageTesController@akhiri');
            Route::post('', 'ManageTesController@store');
            Route::put('/{id}', 'ManageTesController@update');
            Route::delete('/{id}', 'ManageTesController@destroy');
        });
        Route::get('recap', 'AssessmentController@recap');
    });
});

Route::group(['prefix' => 'participant'], function () {
    Route::group(['middleware' => 'auth:participant'], function () {
        Route::get('profile','DashboardParticipantController@profile');
        Route::post('profile','DashboardParticipantController@update');
        
        Route::get('getmapel','DashboardParticipantController@getMapel');
        Route::post('validateToken','DashboardParticipantController@verifTokenMapel');
        Route::get('detail_informasi','DashboardParticipantController@detailInformasi');
        
        Route::group(['prefix' => 'soal'], function () {
            // Ujian
            // get Soal
            Route::get('/ujian/subject/','SoalController@getSubjectSoal');
            Route::post('/ujian/submit','SoalController@submit');
            Route::post('/ujian/cek','SoalController@cek');
            Route::get('/count/{id}','SoalController@countSoal');
        });
    });
});


// file upload
// Route::post('/soal/file/upload','SoalController@fileUpload');;
Route::post('/soal/file/upload','SoalController@imageUploadPost');;
