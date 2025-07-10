<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request): View
    {
        return view('posts.index', [
            "posts" => $this->postService->index($request),
        ]);
    }

    public function show(int $id): View
    {
        return view('posts.show', [
            'post' => $this->postService->show($id),
        ]);
    }
}
