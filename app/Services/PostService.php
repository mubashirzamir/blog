<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class PostService
{
    public function index(Request $request): LengthAwarePaginator
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'user' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        return Post::with('user', 'comments')
            ->when($request->input('title'), function (Builder $query) use ($request) {
                return $query->where('title', 'like', '%' . $request->input('title') . '%');
            })
            ->when($request->input('search'), function (Builder $query) use ($request) {
                return $query->whereHas('user', function (Builder $q) use ($request) {
                    $q->where('name', 'like', '%' . $request->input('search') . '%');
                });
            })->when($request->input('start_date'), function (Builder $query) use ($request) {
                return $query->whereDate('created_at', '>=', $request->input('start_date'));
            })
            ->when($request->input('end_date'), function (Builder $query) use ($request) {
                return $query->whereDate('created_at', '<=', $request->input('end_date'));
            })
            ->paginate();
    }

    public function show(int $id): Post
    {
        return Post::with('user', 'comments')->findOrFail($id);
    }
}
