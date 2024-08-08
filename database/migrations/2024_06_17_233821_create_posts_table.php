<?php

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('post_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained('users', 'id', 'posts_user_id')->cascadeOnDelete();
            $table->foreignIdFor(PostCategory::class)->constrained('post_categories', 'id', 'post_category_id')->cascadeOnDelete();
            //$table->foreignId('user_id')->constrained('users', 'id','posts_user_id')->onDelete('cascade');
            //$table->foreignId('category_id')->constrained('post_categories', 'id', 'posts_categories_id')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('forewords')->nullable();
            $table->text('content');
            $table->string('thumbnail')->nullable();
            $table->boolean('published')->default(true);
            $table->timestamps();
        });

        Schema::create('post_images', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(Post::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('post_images');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_categories');
    }
};
