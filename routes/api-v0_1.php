<?php

use App\Http\Controllers\API\V0_1\AuthController;
use App\Http\Controllers\API\V0_1\EventController;
use Illuminate\Support\Facades\Route;

/**
 * @apiDefine AcceptHeader
 * 
 * @apiHeader Accept=application/json Harus di isi application/json (tidak boleh kosong)
 * @apiHeaderExample Accept
 *     Accept: application/json
 */

/**
 * @apiDefine AuthBearerHeader
 * @apiHeader Authorization Token , harus dengan format Bearer(spasi){token}
 * @apiHeaderExample Authorization
 *     Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c
 */

/**
 * @apiDefine AuthWithTokenResponse
 * 
 * @apiSuccess {String} access_token   Token akses.
 * @apiSuccess {String} token_type     Tipe Token (Selalu Bearer).
 * @apiSuccess {String} expires_in     Waktu token kadaluarsa dalam detik.
 * @apiSuccess {Object} user           Informasi user yang sudah login.
 * @apiSuccess {Number} user.id        ID user.
 * @apiSuccess {String} user.username  Username.
 * @apiSuccess {String} user.name      Display name user.
 * @apiSuccess {String} user.photo_url URL photo profile
 *
 * @apiSuccessExample 200
 *     HTTP/1.1 200 OK
 *     {
 *         "access_token" : "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c",
 *         "token_type" : "bearer",
 *         "expires_in" : 3600,
 *         "user" : {
 *             "id": 1,
 *             "name": "Fany Muhammad Fahmi Kamilah",
 *             "username": "emfahmika",
 *             "photo_url": null,
 *             "email": "fahmikamilah@gmail.com",
 *             "created_at": "2023-12-06T11:15:26.000000Z",
 *             "updated_at": "2023-12-06T11:15:26.000000Z",
 *         }
 *     }
 *
 */

/**
 * @api {post} /api/v0.1/login Login.
 * @apiVersion 0.1.0
 * @apiName Login
 * @apiGroup Auth
 * 
 * @apiUse AcceptHeader
 * @apiUse AuthWithTokenResponse
 *
 * @apiBody {String} username Boleh kosong jika menyertakan email. Prioritas melebihi email untuk login.
 * @apiBody {String} email Boleh kosong jika menyertakan email username.
 * @apiBody {String} password Password Login
 *
 * @apiError (401) message Pesan error.
 *
 * @apiErrorExample 401
 *     HTTP/1.1 401 Unauthorized
 *     {
 *       "message" : "Username atau Password salah"
 *     }
 */
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

/**
 * @api {post} /api/v0.1/register Register.
 * @apiVersion 0.1.0
 * @apiName Register
 * @apiGroup Auth
 * 
 * @apiUse AcceptHeader
 *
 * @apiBody {String} name Nama pengguna
 * @apiBody {String} username
 * @apiBody {String} email
 * @apiBody {String} password
 * 
 * @apiSuccess (201) {String} access_token   Token akses.
 * @apiSuccess (201) {String} token_type     Tipe Token (Selalu Bearer).
 * @apiSuccess (201) {String} expires_in     Waktu token kadaluarsa dalam detik.
 * @apiSuccess (201) {Object} user           Informasi user yang sudah login.
 * @apiSuccess (201) {Number} user.id        ID user.
 * @apiSuccess (201) {String} user.username  Username.
 * @apiSuccess (201) {String} user.name      Display name user.
 * @apiSuccess (201) {String} user.photo_url URL photo profile
 *
 * @apiSuccessExample 201
 *     HTTP/1.1 201 Created
 *     {
 *         "user": {
 *             "id": 1,
 *             "name": "Fany Muhammad Fahmi Kamilah",
 *             "username": "emfahmika3",
 *             "photo_url": null,
 *             "email": "fahmikamilah3@gmail.com",
 *             "created_at": "2023-12-09T12:34:51.000000Z",
 *             "updated_at": "2023-12-09T12:34:51.000000Z"
 *         }
 *     }
 * 
 * @apiError (422) {String} message Pesan error.
 * @apiError (422) {Object} errors Pesan error per-field.
 *
 * @apiErrorExample 422
 *     HTTP/1.1 422 Unprocessable Content
 *     {
 *         "message": "Nama wajib diisi. (dan 2 kesalahan lainnya)",
 *         "errors": {
 *             "name": [
 *                  "Nama wajib diisi."
 *              ],
 *             "username": [
 *                  "Nama pengguna sudah ada sebelumnya."
 *              ],
 *              "email": [
 *                  "Surel sudah ada sebelumnya."
 *              ]
 *         }
 *     }
 */
Route::post('/register', [AuthController::class, 'register'])->name('api.register');

Route::middleware(['auth:api'])->group(function () {

    /**
     * @api {post} /api/v0.1/refresh Refresh Token.
     * @apiVersion 0.1.0
     * @apiName Refresh
     * @apiGroup Auth
     *
     * @apiUse AuthWithTokenResponse
     * @apiUse AcceptHeader
     * @apiUse AuthBearerHeader
     */
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('api.refresh');

    /**
     * @api {post} /api/v0.1/logout Logout User.
     * @apiVersion 0.1.0
     * @apiName Logout
     * @apiGroup Auth
     * 
     * @apiUse AcceptHeader
     * @apiUse AuthBearerHeader
     *
     * @apiSuccess (200) {String} message Pesan sukses.
     * @apiSuccessExample 200
     *     HTTP/1.1 200 OK
     *     {
     *         "message": "Berhasil logout"
     *     }
     */
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    /**
     * @api {get} /api/v0.1/event Get Event
     * @apiVersion 0.1.0
     * @apiName GetEvent
     * @apiGroup Event
     * 
     * @apiUse AcceptHeader
     * @apiUse AuthBearerHeader
     *
     * @apiSuccess (200) {Object[]} data Data dari response
     * @apiSuccess (200) {Number} data.id Id event
     * @apiSuccess (200) {Object} data.host Data akun host event
     * @apiSuccess (200) {Number} data.host.id Id Host
     * @apiSuccess (200) {String} data.host.name Nama lengkap host
     * @apiSuccess (200) {String} data.host.username Username host
     * @apiSuccess (200) {String} data.host.photo_url Url photo pforile host
     * @apiSuccess (200) {String} data.host.email Email host
     * @apiSuccess (200) {String} data.host.created_at Taggal dibuat akun host (format ISO 8601)
     * @apiSuccess (200) {String} data.host.updated_at Taggal diedit akun host (format ISO 8601)
     * @apiSuccess (200) {Object[]} data.participant Data data akun participant event
     * @apiSuccess (200) {Number} data.participant.id Id participant
     * @apiSuccess (200) {String} data.participant.name Nama lengkap participant
     * @apiSuccess (200) {String} data.participant.username Username participant
     * @apiSuccess (200) {String} data.participant.photo_url Url photo pforile participant
     * @apiSuccess (200) {String} data.participant.email Email participant
     * @apiSuccess (200) {String} data.participant.created_at Taggal dibuat akun participant (format ISO 8601)
     * @apiSuccess (200) {String} data.participant.updated_at Taggal diedit akun participant (format ISO 8601)
     * @apiSuccess (200) {String} data.name Nama event
     * @apiSuccess (200) {String} data.thumbnail_url Url gambar thumbnail event
     * @apiSuccess (200) {String} data.description Deskripsi event
     * @apiSuccess (200) {String} data.date Tanggal event
     * @apiSuccess (200) {Number} data.start_time Waktu dimulai event (format detik)
     * @apiSuccess (200) {Number} data.end_time Waktu berakhir event (format detik)
     * @apiSuccess (200) {Number} data.lat Latitide event (format float)
     * @apiSuccess (200) {Number} data.lon Longitude event (format float)
     * @apiSuccess (200) {Number} data.max_participant Maksimal jumlah participant
     * 
     * @apiSuccessExample 200
     *     HTTP/1.1 200 OK
     *     {
     *         "data": [
     *                 {
     *                 "id": 1,
     *                 "host": {
     *                     "id": 1,
     *                     "name": "Fany Muhammad Fahmi Kamilah",
     *                     "username": "emfahmika",
     *                     "photo_url": null,
     *                     "email": "fahmikamilah@gmail.com",
     *                     "created_at": "2023-12-09T13:16:40.000000Z",
     *                     "updated_at": "2023-12-09T13:16:40.000000Z"
     *                 },
     *                 "participant": [
     *                     {
     *                         "id": 1,
     *                         "name": "Fany Muhammad Fahmi Kamilah",
     *                         "username": "emfahmika",
     *                         "photo_url": null,
     *                         "email": "fahmikamilah@gmail.com",
     *                         "created_at": "2023-12-09T13:16:40.000000Z",
     *                         "updated_at": "2023-12-09T13:16:40.000000Z"
     *                     },
     *                     {
     *                         "id": 2,
     *                         "name": "Fany Muhammad Fahmi Kamilah",
     *                         "username": "emfahmika",
     *                         "photo_url": null,
     *                         "email": "fahmikamilah@gmail.com",
     *                         "created_at": "2023-12-09T13:16:40.000000Z",
     *                         "updated_at": "2023-12-09T13:16:40.000000Z"
     *                     }
     *                 ],
     *                 "name": "Event Name",
     *                 "thumbnail_url": "http://localhost:8000/storage/event-thumbnail/GCs6O5OE6OAcTfsMECBKpZJZOk0PHfwTfk2hrJ3h.jpg",
     *                 "description": "Event description",
     *                 "date": "2023-12-09",
     *                 "start_time": 21600,
     *                 "end_time": 25200,
     *                 "lat": -6.983162,
     *                 "lon": 108.432557,
     *                 "max_participant": 10
     *             },
     *             {
     *                 "id": 2,
     *                 "host": {
     *                     "id": 1,
     *                     "name": "Fany Muhammad Fahmi Kamilah",
     *                     "username": "emfahmika",
     *                     "photo_url": null,
     *                     "email": "fahmikamilah@gmail.com",
     *                     "created_at": "2023-12-09T13:16:40.000000Z",
     *                     "updated_at": "2023-12-09T13:16:40.000000Z"
     *                 },
     *                 "participant": [
     *                     {
     *                         "id": 1,
     *                         "name": "Fany Muhammad Fahmi Kamilah",
     *                         "username": "emfahmika",
     *                         "photo_url": null,
     *                         "email": "fahmikamilah@gmail.com",
     *                         "created_at": "2023-12-09T13:16:40.000000Z",
     *                         "updated_at": "2023-12-09T13:16:40.000000Z"
     *                     },
     *                     {
     *                         "id": 2,
     *                         "name": "Fany Muhammad Fahmi Kamilah",
     *                         "username": "emfahmika",
     *                         "photo_url": null,
     *                         "email": "fahmikamilah@gmail.com",
     *                         "created_at": "2023-12-09T13:16:40.000000Z",
     *                         "updated_at": "2023-12-09T13:16:40.000000Z"
     *                     }
     *                 ],
     *                 "name": "Event Name",
     *                 "thumbnail_url": "http://localhost:8000/storage/event-thumbnail/GCs6O5OE6OAcTfsMECBKpZJZOk0PHfwTfk2hrJ3h.jpg",
     *                 "description": "Event description",
     *                 "date": "2023-12-09",
     *                 "start_time": 21600,
     *                 "end_time": 25200,
     *                 "lat": -6.983162,
     *                 "lon": 108.432557,
     *                 "max_participant": 10
     *             }
     *         ]
     *     }
     */

    /**
     * @apiDefine EventResponse
     * 
     * @apiSuccess (200) {String} message Pesan status.
     * @apiSuccess (200) {Object} data Data dari response
     * @apiSuccess (200) {Number} data.id Id event
     * @apiSuccess (200) {Object} data.host Data akun host event
     * @apiSuccess (200) {Number} data.host.id Id Host
     * @apiSuccess (200) {String} data.host.name Nama lengkap host
     * @apiSuccess (200) {String} data.host.username Username host
     * @apiSuccess (200) {String} data.host.photo_url Url photo pforile host
     * @apiSuccess (200) {String} data.host.email Email host
     * @apiSuccess (200) {String} data.host.created_at Taggal dibuat akun host (format ISO 8601)
     * @apiSuccess (200) {String} data.host.updated_at Taggal diedit akun host (format ISO 8601)
     * @apiSuccess (200) {Object[]} data.participant Data data akun participant event
     * @apiSuccess (200) {Number} data.participant.id Id participant
     * @apiSuccess (200) {String} data.participant.name Nama lengkap participant
     * @apiSuccess (200) {String} data.participant.username Username participant
     * @apiSuccess (200) {String} data.participant.photo_url Url photo pforile participant
     * @apiSuccess (200) {String} data.participant.email Email participant
     * @apiSuccess (200) {String} data.participant.created_at Taggal dibuat akun participant (format ISO 8601)
     * @apiSuccess (200) {String} data.participant.updated_at Taggal diedit akun participant (format ISO 8601)
     * @apiSuccess (200) {String} data.name Nama event
     * @apiSuccess (200) {String} data.thumbnail_url Url gambar thumbnail event
     * @apiSuccess (200) {String} data.description Deskripsi event
     * @apiSuccess (200) {String} data.date Tanggal event
     * @apiSuccess (200) {Number} data.start_time Waktu dimulai event (format detik)
     * @apiSuccess (200) {Number} data.end_time Waktu berakhir event (format detik)
     * @apiSuccess (200) {Number} data.lat Latitide event (format float)
     * @apiSuccess (200) {Number} data.lon Longitude event (format float)
     * @apiSuccess (200) {Number} data.max_participant Maksimal jumlah participant
     * 
     * @apiSuccessExample 200
     *     HTTP/1.1 200 OK
     *     {
     *         "message": "Success",
     *         "data": {
     *             "id": 1,
     *             "host": {
     *                 "id": 1,
     *                 "name": "Fany Muhammad Fahmi Kamilah",
     *                 "username": "emfahmika",
     *                 "photo_url": null,
     *                 "email": "fahmikamilah@gmail.com",
     *                 "created_at": "2023-12-09T13:16:40.000000Z",
     *                 "updated_at": "2023-12-09T13:16:40.000000Z"
     *             },
     *             "participant": [
     *                 {
     *                     "id": 1,
     *                     "name": "Fany Muhammad Fahmi Kamilah",
     *                     "username": "emfahmika",
     *                     "photo_url": null,
     *                     "email": "fahmikamilah@gmail.com",
     *                     "created_at": "2023-12-09T13:16:40.000000Z",
     *                     "updated_at": "2023-12-09T13:16:40.000000Z"
     *                 },
     *                 {
     *                     "id": 2,
     *                     "name": "Fany Muhammad Fahmi Kamilah",
     *                     "username": "emfahmika",
     *                     "photo_url": null,
     *                     "email": "fahmikamilah@gmail.com",
     *                     "created_at": "2023-12-09T13:16:40.000000Z",
     *                     "updated_at": "2023-12-09T13:16:40.000000Z"
     *                 }
     *             ],
     *             "name": "Event Name",
     *             "thumbnail_url": "http://localhost:8000/storage/event-thumbnail/GCs6O5OE6OAcTfsMECBKpZJZOk0PHfwTfk2hrJ3h.jpg",
     *             "description": "Event description",
     *             "date": "2023-12-09",
     *             "start_time": 21600,
     *             "end_time": 25200,
     *             "lat": -6.983162,
     *             "lon": 108.432557,
     *             "max_participant": 10
     *         }
     *     }
     */

    /**
     * @api {get} /api/v0.1/event/:id Get Specific Event
     * @apiVersion 0.1.0
     * @apiName GetSpecificEvent
     * @apiGroup Event
     * 
     * @apiParam id Id event yang ingin di ambil
     *
     * @apiUse AcceptHeader
     * @apiUse AuthBearerHeader
     * @apiUse EventResponse
     *
     */

    /**
     * @api {post} /api/v0.1/event Create Event
     * @apiVersion 0.1.0
     * @apiName CreateEvent
     * @apiGroup Event
     * 
     * @apiBody {String} name Nama event
     * @apiBody {Image} thumbnail Thumbnail gambar event
     * @apiBody {String} description Desktipsi Event
     * @apiBody {String} date Tanggal event format (YYYY-MM-DD)
     * @apiBody {Number} start_time Waktu dimulai event (format detik)
     * @apiBody {Number} end_time Waktu berakhir event (format detik)
     * @apiBody {Number} lat Latitide event (format float)
     * @apiBody {Number} lon Longitude event (format float)
     * @apiBody {Number} max_participant Maksimal jumlah participant
     * 
     * @apiUse AcceptHeader
     * @apiUse AuthBearerHeader
     * 
     * @apiSuccess (201) {String} message Pesan status.
     * @apiSuccess (201) {Object} data Data dari response
     * @apiSuccess (201) {Number} data.id Id event
     * @apiSuccess (201) {Object} data.host Data akun host event
     * @apiSuccess (201) {Number} data.host.id Id Host
     * @apiSuccess (201) {String} data.host.name Nama lengkap host
     * @apiSuccess (201) {String} data.host.username Username host
     * @apiSuccess (201) {String} data.host.photo_url Url photo pforile host
     * @apiSuccess (201) {String} data.host.email Email host
     * @apiSuccess (201) {String} data.host.created_at Taggal dibuat akun host (format ISO 8601)
     * @apiSuccess (201) {String} data.host.updated_at Taggal diedit akun host (format ISO 8601)
     * @apiSuccess (201) {Object[]} data.participant Data data akun participant event
     * @apiSuccess (201) {Number} data.participant.id Id participant
     * @apiSuccess (201) {String} data.participant.name Nama lengkap participant
     * @apiSuccess (201) {String} data.participant.username Username participant
     * @apiSuccess (201) {String} data.participant.photo_url Url photo pforile participant
     * @apiSuccess (201) {String} data.participant.email Email participant
     * @apiSuccess (201) {String} data.participant.created_at Taggal dibuat akun participant (format ISO 8601)
     * @apiSuccess (201) {String} data.participant.updated_at Taggal diedit akun participant (format ISO 8601)
     * @apiSuccess (201) {String} data.name Nama event
     * @apiSuccess (201) {String} data.thumbnail_url Url gambar thumbnail event
     * @apiSuccess (201) {String} data.description Deskripsi event
     * @apiSuccess (201) {String} data.date Tanggal event
     * @apiSuccess (201) {Number} data.start_time Waktu dimulai event (format detik)
     * @apiSuccess (201) {Number} data.end_time Waktu berakhir event (format detik)
     * @apiSuccess (201) {Number} data.lat Latitide event (format float)
     * @apiSuccess (201) {Number} data.lon Longitude event (format float)
     * @apiSuccess (201) {Number} data.max_participant Maksimal jumlah participant
     * 
     * @apiSuccessExample 201
     *     HTTP/1.1 201 Created
     *     {
     *         "message": "Success",
     *         "data": {
     *             "id": 1,
     *             "host": {
     *                 "id": 1,
     *                 "name": "Fany Muhammad Fahmi Kamilah",
     *                 "username": "emfahmika",
     *                 "photo_url": null,
     *                 "email": "fahmikamilah@gmail.com",
     *                 "created_at": "2023-12-09T13:16:40.000000Z",
     *                 "updated_at": "2023-12-09T13:16:40.000000Z"
     *             },
     *             "participant": [
     *                 {
     *                     "id": 1,
     *                     "name": "Fany Muhammad Fahmi Kamilah",
     *                     "username": "emfahmika",
     *                     "photo_url": null,
     *                     "email": "fahmikamilah@gmail.com",
     *                     "created_at": "2023-12-09T13:16:40.000000Z",
     *                     "updated_at": "2023-12-09T13:16:40.000000Z"
     *                 },
     *                 {
     *                     "id": 2,
     *                     "name": "Fany Muhammad Fahmi Kamilah",
     *                     "username": "emfahmika",
     *                     "photo_url": null,
     *                     "email": "fahmikamilah@gmail.com",
     *                     "created_at": "2023-12-09T13:16:40.000000Z",
     *                     "updated_at": "2023-12-09T13:16:40.000000Z"
     *                 }
     *             ],
     *             "name": "Event Name",
     *             "thumbnail_url": "http://localhost:8000/storage/event-thumbnail/GCs6O5OE6OAcTfsMECBKpZJZOk0PHfwTfk2hrJ3h.jpg",
     *             "description": "Event description",
     *             "date": "2023-12-09",
     *             "start_time": 21600,
     *             "end_time": 25200,
     *             "lat": -6.983162,
     *             "lon": 108.432557,
     *             "max_participant": 10
     *         }
     *     }
     */
    Route::post('/event/{event}/join', [EventController::class, 'join']);
    Route::post('/event/{event}/disjoin', [EventController::class, 'disjoin']);
    Route::apiResource('event', EventController::class);
});
