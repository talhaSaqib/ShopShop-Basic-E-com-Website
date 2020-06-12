<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class CommentsController extends Controller
{
    public function addreview(Request $request)
    {
        $validator = Validator::make($request->all(),                   //formData by ajax is only working for this validation, not via validate()
            [
                'rating' => 'required|numeric|min:0|max:10',
                'review' => 'required|max:2000',
                'product' => 'required'
            ]);
        if($validator->fails())
        {
            return response()->json(['error' =>
                'Validation Failed!

*Both Rating and Review fields are required
*Rating can only be 0 - 10
*Review can only be 2000 letters long'], 200);           //file size is not changing
        }

        $user = Auth::user();
        $rating = $request['rating'];
        $review = $request['review'];
        $productID = $request['product'];

        $comment = new Comments();
        $comment->rating = $rating;
        $comment->review = $review;
        $comment->user_id = $user->id;
        $comment->product_id = $productID;

        if($comment->save())
        {
            $comments = Comments::join('users', 'users.id', '=', 'comments.user_id')->where('product_id', $productID)->orderBy('comments.created_at', 'desc')->get();
            $view = View::make('layouts.comments')->with('comments', $comments)->render();

            return response()->json(['success' => 'success', 'html' => $view], 200);
        }
        return response()->json(['error' => '*Error: Couldn\'t add the review!'], 200);
    }

    public function getComments($productID)
    {
        $comments = Comments::join('users', 'users.id', '=', 'comments.user_id')->where('product_id', $productID)->orderBy('comments.created_at', 'desc')->get();
        return $comments;
    }

    public function countReviews($productID)
    {
        $count = Comments::where('product_id', $productID)->count();
        return $count;
    }

    public function avgRating($productID)
    {
        $avg = Comments::where('product_id', $productID)->avg('rating');
        return $avg;
    }

}
