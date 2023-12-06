<?php

use App\Http\Controllers\API\V0_1\AuthController;
use Illuminate\Support\Facades\Route;

/**
 * @apiDefine AcceptHeader
 * 
 * @apiHeader accept=application/json Harus di isi application/json (tidak boleh kosong)
 * @apiHeaderExample
 *     Accept: application/json
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
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         'access_token' => eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c,
 *         'token_type' => 'bearer',
 *         'expires_in' => 3600,
 *         'user' => {
 *             "id": 2
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
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 401 Unauthorized
 *     {
 *       "message" => "Username atau Password salah"
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
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 201 Created
 *     {
 *         "id": 2
 *         "name": "Fany Muhammad Fahmi Kamilah",
 *         "username": "emfahmika",
 *         "photo_url": null,
 *         "email": "fahmikamilah@gmail.com",
 *         "created_at": "2023-12-06T11:15:26.000000Z",
 *     }
 * 
 * @apiError (422) {String} message Pesan error.
 * @apiError (422) {Object} errors Pesan error per-field.
 *
 * @apiErrorExample Error-Response:
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
	 */
	Route::post('/refresh', [AuthController::class, 'refresh'])->name('api.refresh');

	/**
	 * @api {post} /api/v0.1/logout Logout User.
	 * @apiVersion 0.1.0
	 * @apiName Logout
	 * @apiGroup Auth
	 * 
	 * @apiUse AcceptHeader
	 *
	 */
	Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
});
