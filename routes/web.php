<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PnsController;
use App\Http\Controllers\PnsCartController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/import-pns-products', [PnsController::class, 'index']);
    Route::post('/add-to-cart', [PnsCartController::class, 'addToCart'])->name('addtocart');

});


Route::get('/', [PnsController::class, 'listProduct'])->name('pns.listproductall');
Route::get('/pnsproduct/{category_id}', [PnsController::class, 'listProduct'])->name('pns.listproduct');
Route::get('/product/{id}', [PnsController::class, 'product'])->name('pns.product');
Route::get("/extra-low", [PnsController::class, 'listProduct'])->name('pns.aextralowall');
Route::get("/extra-low/{category_id}", [PnsController::class, 'listProduct'])->name('pns.extralow');
Route::get('/everyday-low', [PnsController::class, 'listProduct'])->name('pns.everydaylowall');
Route::get('/everyday-low/{category_id', [PnsController::class, 'listProduct'])->name('pns.everydaylow');

require __DIR__.'/auth.php';
