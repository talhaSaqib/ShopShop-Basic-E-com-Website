<?php

namespace App\Http\Controllers;

use App\Products;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RouteController extends Controller
{
    public function toSignlog()
    {
        return view('Signlog');
    }

    public function toProducts()
    {
        $products = Products::orderBy('created_at', 'desc')->get();
        return view('products', ['products' => $products]);
    }

    public function getProfile()
    {
        $products = Products::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('profile', ['user' => Auth::user(), 'products' => $products]);
    }

    public function getUser($user_id)
    {
        $products = Products::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
        $user = User::where('id',$user_id)->first();

        return view('profile', ['user' => $user, 'products' => $products]);
    }
}
