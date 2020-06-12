<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function SignUp(Request $request)
    {
        $this->validate($request,
            [
                'email' => 'required|email|unique:users',
                'username' => 'required|max:120|unique:users',
                'password' => 'required|min:5'
            ]);

        $email = $request['email'];
        $username = $request['username'];
        $password = bcrypt($request['password']);

        $user = new User();
        $user->email = $email;
        $user->username = $username;
        $user->password = $password;

        $user->save();
        Auth::login($user);
        return redirect()->route('products');
    }

    public function login(Request $request)
    {
        $this->validate($request,
            [
                'username1' => 'required|max:120',
                'password1' => 'required|min:5'
            ]);

        if(Auth::attempt( ['username' => $request['username1'], 'password' => $request['password1']] ))
        {
            return redirect()->route('products');
        }
        return redirect()->back();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function changeDp(Request $request)
    {
        /*$this->validate($request,
            [
                'data.*.check' => 'required|max:10000|email|mimes:jpeg,jpg,png,gif'
            ]); */                                                                     //not working

        $user = Auth::user();
        $image = $request->file('img');

        if($image)
        {
            $ext = $image->getClientOriginalExtension();
            $allowed = ['jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF'];
            if(in_array($ext, $allowed))
            {
                $filename = 'images/'.$user->email.'.'.$ext;
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $filename);

                $user->image = $filename;
                $user->update();

                return response()->json(['dp' => $filename], 200);
            }
            else
            {
                return response()->json(['msg1' => 'File is not an Image'], 200);
            }
        }
        return response()->json(['msg' => 'error uploading image'], 200);
    }
}
