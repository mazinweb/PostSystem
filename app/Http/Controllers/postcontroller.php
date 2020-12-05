<?php

namespace App\Http\Controllers;

use App\postmodel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class postcontroller extends Controller
{
    public function createNewPost(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3',
            'body' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error','errors' =>$validator->errors()]);
        }

        $post = new postmodel();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();
        return response()->json(['status' => 'success','data' =>$post]);
    }


    public function getAllpost(){
        $allpost = postmodel::all();
        return response()->json(['status' => 'success','data' =>$allpost]);
    }

    public function updatePost(Request $request , $postid){

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3',
            'body' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error','errors' =>$validator->errors()]);
        }

        $post = postmodel::find($postid);
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();
        return response()->json(['status' => 'success','data' =>$post]);
    }

    public function deletepost($postid){

        $res = postmodel::destroy($postid);
        $allpost = postmodel::all();
        if($res){
        return response()->json(['status' => 'success','data' =>$allpost]);
        }
        else {
            return response()->json(['status' => 'error','data' =>$res]);
        }
    }

}
