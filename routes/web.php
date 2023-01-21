<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

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

Route::get('/', [PostController::class,"index"])->name("indexroute");

Route::get("/posts.show/{post}",[PostController::class,"show"])
->name("showroute")
->where("post","[0-9]+");

Route::get("/posts.create",[PostController::class,"create"])
->name("createroute");

Route::post("/posts.store",[PostController::class,"store"])
->name("storeroute");

Route::get("/post.edit/{post}",[PostController::class,"edit"])
->name("editroute")
->where("post","[0-9]+");

Route::patch("/post.update/{post}",[PostController::class,"update"])
->name("updateroute");

Route::patch("/post.update/{post}",[Postcontroller::class,"update"])
->name("updateroute");

Route::delete("/posts.delete{post}",[PostController::class,"delete"])
->name("deleteroute");

Route::post("/comments.create",[CommentController::class,"create"])
->name("cmcreateroute");

Route::delete("/commetns.delete{comment}",[CommentController::class,"delete"])->name("cmdeleteroute");