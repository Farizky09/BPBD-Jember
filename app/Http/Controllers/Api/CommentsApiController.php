<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Comments;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsApiController extends Controller
{
    public function index()
    {
        $comments = Comments::with('user')->orderByDesc('created_at')->get();

        $comments = $comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'news_id' => $comment->news_id,
                'user_id' => $comment->user_id,
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
                'user_name' => $comment->user->name ?? 'Pengguna',
                'image_avatar' => $comment->user && $comment->user->image_avatar
                    ? asset('storage/' . $comment->user->image_avatar)
                    : null,
            ];
        });

        return response()->json($comments);
    }

    public function getById($id)
    {
        try {
            $comment = Comments::with(['user', 'news'])->find($id);
            if (!$comment) {
                return ResponseHelper::error('Komentar tidak ditemukan');
            }
            return ResponseHelper::success('Berhasil mendapatkan komentar', $comment);
        } catch (\Exception $e) {
            return ResponseHelper::error('Terjadi kesalahan saat mendapatkan komentar', $e->getMessage());
        }
    }

    public function getByNewsId($newsId)
    {
        try {
            $comments = Comments::where('news_id', $newsId)
                ->with(['user'])
                ->orderByDesc('created_at')
                ->get();

            $comments = $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'news_id' => $comment->news_id,
                    'user_id' => $comment->user_id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'user_name' => $comment->user->name ?? 'Pengguna',
                    'image_avatar' => $comment->user && $comment->user->image_avatar
                    ? asset('storage/' . $comment->user->image_avatar)
                    : null,
                ];
            });

            return ResponseHelper::success('Berhasil mendapatkan komentar', $comments);
        } catch (\Exception $e) {
            return ResponseHelper::error('Terjadi kesalahan saat mendapatkan komentar', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'news_id' => 'required|exists:news,id',
                'content' => 'required|string|min:3|max:1000',
            ]);

            $news = News::findOrFail($request->news_id);

            if ($news->status !== 'published') {
                return ResponseHelper::error(
                    'Tidak dapat menambahkan komentar pada berita yang belum dipublikasikan.'
                );
            }

            $comment = Comments::create([
                'news_id' => $request->news_id,
                'user_id' => Auth::id(),
                'content' => $request->content,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Komentar berhasil dibuat.',
                'comment' => [
                    'user_name' => Auth::user()->name,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->diffForHumans()
                ]
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::error('Terjadi kesalahan saat membuat komentar', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $comment = Comments::find($id);
            if (!$comment) {
                return ResponseHelper::error('Komentar tidak ditemukan');
            }
            $comment->update([
                'content' => $request->content,
            ]);
            return ResponseHelper::success('Berhasil mengubah komentar', $comment);
        } catch (\Exception $e) {
            return ResponseHelper::error('Terjadi kesalahan saat mengubah komentar', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $comment = Comments::find($id);
            if (!$comment) {
                return ResponseHelper::error('Komentar tidak ditemukan');
            }
            $comment->delete();
            return ResponseHelper::success('Berhasil menghapus komentar', $comment);
        } catch (\Exception $e) {
            return ResponseHelper::error('Terjadi kesalahan saat menghapus komentar', $e->getMessage());
        }
    }
}
