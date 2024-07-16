<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::whereNull('parent_id')->with('replies')->paginate(25);
        return response()->json($comments);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|regex:/^[a-zA-Z0-9]+$/',
            'email' => 'required|email',
            'home_page' => 'nullable|url',
            'captcha' => 'required|captcha',
            'text' => 'required|string',
            'parent_id' => 'nullable|integer|exists:comments,id'
        ]);
    
        $comment = Comment::create($request->all());
        return response()->json($comment);
    }
    
}
