<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{

    public function index(){
        return view("index")->with(["posts"=>Post::latest()->get()]);
    }

    public function show(Post $post){
        $comments=Comment::where("post_id","=",$post->id)->latest()->get();
        
        return view("posts.show")->with(["post"=>$post,"comments"=>$comments]);
    }

    public function create(){
        return view("posts.create");
    }

    public function store(PostRequest $request)
    {
        $post = new Post();
        $post->body = $request->input("body");
        $post->title = $request->input("title");
        $post->save();

        return redirect()->route("indexroute");
    }

    public function delete(Post $post){
        $post->delete();
        return redirect()->route("indexroute");
    }

    public function edit(Post $post){
        return view("posts.edit")->with(["post"=>$post]);
    }


    public function update(PostRequest $request,Post $post){
        $updatepost=Post::find($post["id"]);
        $updatepost->title=$request->input("title");
        $updatepost->body=$request->input("body");
        $updatepost->save();

        return redirect()->route("indexroute");
    }
}
