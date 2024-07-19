<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function home()
    {
        return view('welcome');
    }
    public function index(Request $request)
    {
        $sortBy = $request->query('sortBy', 'created_at');
        $order = $request->query('order', 'desc'); // 'desc' для LIFO

        $comments = Comment::with('replies') // Или любой другой метод для получения комментариев
        ->orderBy($sortBy, $order)
            ->paginate(10); // Пагинация, если требуется

        return response()->json([
            'data' => $comments->items(),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'home_page' => 'nullable|url',
            'captcha' => 'required|string',
            'text' => 'required|string',
            'parent_id' => 'nullable|integer|exists:comments,id'
        ]);
        $user = Auth::user();

        $comment = Comment::create([
            'user_name' => $user->name,
            'name' => $user->name,
            'email' => $user->email,
            'home_page' => $request->home_page,
            'text' => $request->text,
            'parent_id' => $request->parent_id,
            'user_id' => $user->id,
        ]);
        return response()->json($comment);
    }
    public function storeReply(Request $request, Comment $comment)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'home_page' => 'nullable|url|max:255',
            'text' => 'required|string',
        ]);
        $user = Auth::user();
        // Create a new reply
        $reply = new Comment([
            'user_name' => $user->name,
            'email' => $user->email,
            'home_page' => $validatedData['home_page'],
            'text' => $validatedData['text'],
            'parent_id' => $comment->id,
            'user_id' => $user->id,
        ]);

        // Save the reply
        $comment->replies()->save($reply);

        return response()->json($reply, 201);
    }

}
