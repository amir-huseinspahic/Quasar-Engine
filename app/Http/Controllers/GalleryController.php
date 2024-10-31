<?php

namespace App\Http\Controllers;

use App\Models\AppSettings;
use App\Models\GalleryImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class GalleryController extends Controller {

    public function index() {
        if (!$this->galleryEnabled()) {
            return back()->withError(__('toast.page_disabled_error'));
        }

        try {
            if (!auth()->user()->hasPermissionTo('add image to gallery') && !auth()->user()->hasRole('root')) {
                throw new Exception(__('toast.gallery_view_failed'));
            }

            $images = GalleryImage::all()->sortBy('created_at');

            return Inertia::render('AdminPanel/Gallery/Index', ['images' => $images]);
        }
        catch (Exception $e) {
            return Redirect::back()->withError($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'description' => 'nullable|string',
        ]);

        try {
            if (!auth()->user()->hasPermissionTo('add image to gallery') && !auth()->user()->hasRole('root')) {
                throw new Exception(__('toast.gallery_view_failed'));
            }

            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            $imgPath = $request->file('image')->store('gallery', ['disk' => 'gallery_uploads']);

            GalleryImage::create([
                'img_name' => $imageName,
                'img_path' => '/media/' . $imgPath,
                'description' => $request->input('description'),
            ]);

            $response = redirect()->back()->withSuccess(__('toast.gallery_create_successful'));
            return Inertia::location($response);
        }
        catch (Exception $e) {
            return Redirect::back()->withError(__('toast.gallery_create_failed', ['error' => $e->getMessage()]));
        }
    }

    public function destroy($id) {

        try {
            if (!auth()->user()->hasPermissionTo('remove image from gallery') && !auth()->user()->hasRole('root')) {
                throw new Exception(__('toast.gallery_view_failed'));
            }

            $image = GalleryImage::findOrFail($id);
            $image->delete();

            $fullImagePath = public_path() . $image->img_path;
            if (file_exists($fullImagePath)) {
                unlink($fullImagePath);

            }

            return Redirect::back()->withSuccess(__('toast.gallery_delete_successful'));
        }
        catch (Exception $e) {
            return Redirect::back()->withError(__('toast.gallery_delete_failed', ['error' => $e->getMessage()]));
        }
    }

    protected function galleryEnabled() {
        return AppSettings::first()->gallery;
    }
}
