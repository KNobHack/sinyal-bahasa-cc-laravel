<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

/**
 * @api {post} /api/v0.1/login Login.
 * @apiVersion 0.1.0
 * @apiName Login
 * @apiGroup Auth
 *
 * @apiBody {String} username Boleh kosong jika menyertakan email. Prioritas melebihi email untuk login.
 * @apiBody {String} email Boleh kosong jika menyertakan email username.
 * @apiBody {String} password Password Login
 *
 * @apiSuccess {string}  access_token  Token akses.
 * @apiSuccess {string}  token_type    Tipe Token (Selalu Bearer).
 * @apiSuccess {string}  expires_in    Waktu token kadaluarsa dalam detik.
 * @apiSuccess {Object}  user          Informasi user yang sudah login.
 * @apiSuccess {Number}  user.id       ID user.
 * @apiSuccess {Number}  user.username Username.
 * @apiSuccess {Number}  user.name     Display name user.
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         'access_token' => eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c,
 *         'token_type' => 'bearer',
 *         'expires_in' => 5000,
 *         'user' => [
 *             'id' => 1,
 *             'username' => emfahmika,
 *             'name' => Fany Muhammad Fahmi Kamilah,
 *     }
 *
 * @apiError (401) CredentialDontMatch Username dan Password tidak ada di dalam database.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 401 Unauthorized
 *     {
 *       "error" => "Username atau Password salah"
 *     }
 */
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware(['auth:api'])->group(function () {

	/**
	 * @api {post} /api/v0.1/refresh Refresh Token.
	 * @apiVersion 0.1.0
	 * @apiName Refresh
	 * @apiGroup Auth
	 *
	 * @apiSuccess {string}  access_token  Token akses.
	 * @apiSuccess {string}  token_type    Tipe Token (Selalu Bearer).
	 * @apiSuccess {string}  expires_in    Waktu token kadaluarsa dalam detik.
	 * @apiSuccess {Object}  user          Informasi user yang sudah login.
	 * @apiSuccess {Number}  user.id       ID user.
	 * @apiSuccess {Number}  user.username Username.
	 * @apiSuccess {Number}  user.name     Display name user.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *         'access_token' => eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c,
	 *         'token_type' => 'bearer',
	 *         'expires_in' => 5000,
	 *         'user' => [
	 *             'id' => 1,
	 *             'username' => emfahmika,
	 *             'name' => Fany Muhammad Fahmi Kamilah,
	 *     }
	 */
	Route::post('/refresh');
});
