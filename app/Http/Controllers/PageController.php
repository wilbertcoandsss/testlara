<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    //
    public function goToAboutMePage(Request $req){
        $product = Product::with('productType')->get();
        $product->productType->name; // Print Type dari Product yang dipilih
        // return 'About Me Page';
        // $name = 'Wilbert';
        $name = $req->input('name', 'Default Wilbert');
        return view('about-me', ['name' => $name]);
    }

    public function goToPostPage(){
        // Select datanya
        // RAW
        // $users = DB::select('SELECT * FROM users JOIN posts ON users.id = posts.id');

        // QB
        // $users = DB::table('users')->get();

        // Eloquent
        $users = User::with('posts')->get();
        return $users;
    }

    public function index(){
        return view('posts.index');
    }

    public function createpostpage(){
        return view('posts.create');
    }
}
