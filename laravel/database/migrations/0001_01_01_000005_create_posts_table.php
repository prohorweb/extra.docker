<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['setting', 'news', 'service', 'share', 'event', 'job', 'trainer', 'card']);
            $table->tinyInteger('status')->default(10);
            $table->integer('position')->default(0);
            $table->string('title');
            $table->string('slug')->nullable();
            $table->text('intro')->nullable();
            $table->longText('content')->nullable();
            $table->string('img')->nullable();

            $table->string('subtitle')->nullable();

            $table->integer('price')->nullable();

            $table->date('date')->nullable();

            $table->boolean('is_paid')->default(false);
            $table->boolean('is_open')->default(true);

            $table->boolean('is_banner')->default(false);
            $table->integer('banner_position')->nullable();
            $table->string('banner_video')->nullable();
            $table->string('banner_img_mobile')->nullable();

            $table->string('tel')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('coordinates')->nullable();
            $table->string('working_hours')->nullable();
            $table->string('working_hours_weekend')->nullable();

            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index(['type', 'status', 'position']);
            $table->unique(['type', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
