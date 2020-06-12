<?php

namespace App\Http\Controllers;

use App\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class ProductController extends Controller
{

    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(),                   //formData by ajax is only working for this validation, not via validate()
        [
            'name' => 'required|max:20',
            'size' => 'required',
            'quality' => 'required|numeric|min:0|max:10',
            'price' => 'required|numeric|min:20|max:5000',
            'desc' => 'required'
            //'img' => 'required|image|mimes:jpeg,jpg,png'
        ]);

        if($validator->fails())
        {
            return response()->json(['error' =>
           'Validation Failed!
           
*Every field is required
*Price can be 20 to 5000 amount only
*Quality can be 0 to 10 only
*Supported image formats are: JPEG, JPG, PNG
*Max file upload size is 2MB'], 200);           //file size is not changing
        }

        $user = Auth::user();
        $image = $request->file('img');

        if($image)
        {
            $ext = $image->getClientOriginalExtension();
            $allowed = ['jpg','jpeg','png','JPG','JPEG','PNG'];
            if(in_array($ext, $allowed))
            {
                $Pname = $request['name'];
                $filename = 'images/'.time().$Pname.'.'.$ext;
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $filename);

                $product = new Products();
                $product->name = $Pname;
                $product->size = $request['size'];
                $product->quality = $request['quality'];
                $product->price = $request['price'];
                $product->description = $request['desc'];
                $product->image = $filename;

                if($user->products()->save($product))
                {
                    $products = Products::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
                    $view = View::make('includes.productsGrid')->with('products', $products)->render();

                    return response()->json(['success' => 'Product Added Successfully', 'html' => $view], 200);
                }

                return response()->json(['error' => "Couldn't add the product!"], 200);
            }
            else
            {
                return response()->json(['error' => 'Only JPG, PNG and JPEG formats are supported'], 200);
            }
        }
        return response()->json(['error' => 'Image not selected'], 200);
    }

    public function getDetails($product_id)
    {
        $product = Products::where('id', $product_id)->first();

        $owner = ProductController::getOwner($product->id);
        $product->setAttribute('owner', $owner->username);

        $comments = (new CommentsController)->getComments($product->id);

        return view('details', ['product' => $product, 'comments' => $comments]);
    }

    public function getOwner($product_id)
    {
        $owner = Products::join('users', 'users.id', '=', 'products.user_id')
                ->select('users.username')
                ->where('products.id', $product_id)
                ->first();

        return $owner;
    }

}
