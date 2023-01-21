<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    public function create(Request $request){

        $request->validate([
                "body"=>"required|min:3"
                    ],
                    [
                "body.required"=>"ない！",
                "body.min"=>"短い！"
                    ]
                );

        $body=$request->body;
        $post_id=$request->post_id;

        $comment=new Comment();
        $comment->body=$body;
        $comment->post_id=$post_id;
        $comment->save();

        return redirect()->route("showroute",$comment->post);

    }

    public function delete(Comment $comment, Request $request){
        $comment->delete();

        return redirect()->route("showroute",$comment->post);
    }
}
