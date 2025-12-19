<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'news_id' => 'required|exists:news,id',
                'content' => 'required|string|min:3|max:1000',
            ]);

            $news = News::findOrFail($request->news_id);
            if ($news->status !== 'published') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat menambahkan komentar pada berita yang belum dipublikasikan.'
                ], 422);
            }
            $comment = Comment::create([
                'news_id' => $request->news_id,
                'user_id' => Auth::id(),
                'content' => $request->content,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Komentar berhasil ditambahkan.',
                'comment' => [
                    'user_name' => Auth::user()->name,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->diffForHumans()
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors()[array_key_first($e->errors())])
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan server: ' . $th->getMessage()
            ], 500);
        }
    }
}
