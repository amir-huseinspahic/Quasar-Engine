<?php

namespace App\Http\Controllers;

use App\Models\PostCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PostCategoriesController extends Controller {
    public function store(Request $request) {
        $request->validate(['name' => ['required', 'string', 'unique:post_categories']]);

        try {
            PostCategory::create([
                'name' => $request->name
            ]);
        }
        catch (Exception $e) {
            return Redirect::back()->withError(__('toast.post_category_create_failed', ['error' => $e->getMessage()]));
        }


        return Redirect::back()->withSuccess(__('toast.post_category_create_successful', ['postCategory' => $request->name]));
    }

    public function destroy($id) {
        try {
            PostCategory::find($id)->delete();
        }
        catch (Exception $e) {
            return Redirect::back()->withError(__('toast.post_category_delete_failed', ['error' => $e->getMessage()]));
        }


        return Redirect::back()->withSuccess(__('toast.post_category_delete_successful'));
    }
}
