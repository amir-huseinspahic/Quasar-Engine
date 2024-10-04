<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class HomepageController extends Controller {
    public function index() : Response {

        $posts = Post::with([
            'category',
            'author' => function($query) {
                $query->select('id', 'name');
            }])
            ->where(function ($query) {
                if (auth()->check()) {
                    if (auth()->user()->settings->show_private_posts === false) {
                        $query->where('published', true);
                    }
                }
                else $query->where('published', true);
            })
            ->whereHas('category', function ($query) {
                $query->where('name', 'NOT LIKE', 'News');
            })
            ->latest()
            ->get()
            ->each(function($post) {
                $post->author->makeHidden('id');
                $post->category->makeHidden('id');
            });

        $news = Post::with([
            'category',
            'author' => function($query) {
                $query->select('id', 'name');
            }])
            ->where(function ($query) {
                if (auth()->check()) {
                    if (auth()->user()->settings->show_private_posts === false) {
                        $query->where('published', true);
                    }
                }
                else $query->where('published', true);
            })
            ->whereHas('category', function ($query) {
                $query->where('name', 'LIKE', 'News');
            })
            ->latest()
            ->get()
            ->each(function($post) {
                $post->author->makeHidden('id');
                $post->category->makeHidden('id');
            });

        $gallery = GalleryImage::all();

        return Inertia::render('Home', [
            'appName' => config('app.name'),
            'posts' => $posts,
            'news' => $news,
            'gallery' => $gallery,
        ]);
    }

    public function postDetails($slug) : Response {
        $post = Post::findBySlug($slug);
        $post->author->makeHidden('id');
        $post->category->makeHidden('id');
        $post->media->makeHidden('id');

        return Inertia::render('PostDetails', [
            'appName' => config('app.name'),
            'post' => $post,
            'postCount' => Post::all()->count(),
            'galleryCount' => GalleryImage::all()->count(),
        ]);
    }

    public function handleHomepageLocale(Request $request) {
        $request->validate([
            'locale' => ['required', 'string'],
        ]);

        $request->session()->put('locale', $request->input('locale'));

        $reponse = redirect()->back();
        return Inertia::location($reponse);
    }
}
