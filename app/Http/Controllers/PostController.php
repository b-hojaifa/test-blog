<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Tag;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    public function index()
    {

        $posts = Post::latest()->approved()->Published()->paginate(6);
        return view('posts', compact('posts'));

    }


    public function details($slug)
    {
        $post = Post::where('slug', $slug)->approved()->Published()->first();
        return $post->user_id;
        $blogKey = 'blog_' . $post->id;
        if (!Session::has($blogKey)){
            $post->increment('view_count');
            Session::put($blogKey, 1);
        }

        $randomposts = Post::approved()->Published()->take(3)->inRandomOrder()->get();
        return view('post', compact('post','randomposts'));
    }

    public function postByCategory($slug)
    {
        $category = Category::where('slug', $slug)->first();
        $posts = $category->posts()->approved()->Published()->get();
        return view('category', compact('category', 'posts'));
    }

    public function postByTag($slug)
    {
        $tag = Tag::where('slug', $slug)->first();
        $posts = $tag->posts()->approved()->Published()->get();
        return view('tag', compact('tag', 'posts'));
    }
}
