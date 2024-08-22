<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostImage;
use App\Models\User;
use App\Models\UserPreferences;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use function PHPSTORM_META\map;

class PostController extends Controller {

    /**
     * @return Response
     * Display a listing of the resource.
     */
    public function index() {

        $userSettings = auth()->user()->settings->first();

        $posts = Post::query()
            ->orderBy('created_at', 'desc')
            ->when(Request::input('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->paginate($userSettings->items_per_page)
            ->withQueryString()
            ->through(fn($post) => [
                'id' => $post->id,
                'title' => $post->title,
                'category' => $post->category->name,
                'author' => $post->author->name,
                'created_at' => $post->created_at,
                'published' => $post->published,
                'thumbnail' => $post->thumbnail,
            ]);

        return Inertia::render('AdminPanel/Posts/Index', [
            'posts' => $posts,
            'userPreferences' => $userSettings,
            'filters' => Request::only(['search', 'category', 'author', 'published']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response {
        $postCategories = PostCategory::all();

        return Inertia::render('AdminPanel/Posts/Create', ['postCategories' => $postCategories]);
    }

    /**
     * Store a newly created resource in storage.
     * TODO: Add image compression
     */
    public function store(PostStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $thumbnailName = null;
        if ($validated['thumbnail']) {
            $thumbnailName = Str::of($validated['title'])->slug('-') . '-' . time() . '.' . $validated['thumbnail']->getClientOriginalExtension();
            $validated['thumbnail']->storeAs('thumbnails', $thumbnailName, ['disk' => 'post-uploads']);
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'post_category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'forewords' => $validated['forewords'],
            'thumbnail' => $thumbnailName,
            'content' => $validated['content'],
            'published' => $validated['published']
        ]);

        $mediaNames = null;
        if ($validated['media']) {
            for ($i = 0; $i < count($validated['media']); $i++) {
                $mediaNames[$i] = Str::of($validated['title'])->slug('-') . '-' . Str::random(10) . '-' . time() . '.' . $validated['media'][$i]->getClientOriginalExtension();
                $validated['media'][$i]->storeAs('media', $mediaNames[$i], ['disk' => 'post-uploads']);

                $post->media()->create([
                    'name' => $mediaNames[$i],
                    'path' => '/media/posts/media/' . $mediaNames[$i],
                ]);
            }
        }

        return Redirect::route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post) : Response {
        $userSettings = auth()->user()->settings->first();

        $userPreferences['locale'] = $userSettings->locale;
        $userPreferences['timezone'] = $userSettings->timezone;
        $userPreferences['date_format'] = $userSettings->date_format;
        $userPreferences['time_format'] = $userSettings->time_format;

        unset($userSettings);


        $post->load('author', 'category', 'media');

        return Inertia::render('AdminPanel/Posts/Show', [
            'post' => $post,
            'userPreferences' => $userPreferences,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post) : Response {
        $post->author;
        $post->category;
        $post->media;

        $postCategories = PostCategory::all();
        $users = User::all('id', 'name');


        return Inertia::render('AdminPanel/Posts/Edit', [
            'post' => $post,
            'postCategories' => $postCategories,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post) : RedirectResponse {
        $validated = $request->validated();

        $newThumbnailName = null;
        $newMediaNames = null;

        if ($validated['new_thumbnail']) {
            $newThumbnailName = Str::of($validated['title'])->slug('-') . '-' . time() . '.' . $validated['new_thumbnail']->getClientOriginalExtension();
            $validated['new_thumbnail']->storeAs('thumbnails', $newThumbnailName, ['disk' => 'post-uploads']);
        }

        if ($validated['new_media']) {
            for ($i = 0; $i < count($validated['new_media']); $i++) {
                $newMediaNames[$i] = Str::of($validated['title'])->slug('-') . '-' . Str::random(10) . '-' . time() . '.' . $validated['new_media'][$i]->getClientOriginalExtension();
                $validated['new_media'][$i]->storeAs('media', $newMediaNames[$i], ['disk' => 'post-uploads']);

                $post->media()->create([
                    'name' => $newMediaNames[$i],
                    'path' => '/media/posts/media/' . $newMediaNames[$i],
                ]);
            }
        }

        $post->update([
            'user_id' => $validated['user_id'],
            'post_category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'forewords' => $validated['forewords'],
            'thumbnail' => $newThumbnailName ?? $validated['thumbnail'],
            'content' => $validated['content'],
            'published' => $validated['published']
        ]);

        return Redirect::route('posts.show', ['post' => $post]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post) {
        $post->media;

        if ($post['thumbnail']) {
            Storage::disk('post-uploads')->delete('media/posts/thumbnails/' . $post['thumbnail']);
        }

        if ($post['media']) {
            foreach ($post['media'] as $media) {
                $post->media()->delete();
                Storage::disk('post-uploads')->delete('media/posts/media/' . $media->name);
            }
        }

        $post->delete();

        return Redirect::route('posts.index');
    }

    public function destroyMedia(Post $post, int $mediaId) : RedirectResponse {
        $post->media()->find($mediaId)->delete();

        return Redirect::route('posts.edit', ['post' => $post]);
    }
}
