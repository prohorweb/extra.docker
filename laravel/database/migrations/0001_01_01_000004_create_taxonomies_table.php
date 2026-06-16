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
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['club', 'category', 'specialization', 'department', 'tag']);
            $table->string('title');
            $table->string('slug');
            $table->integer('position')->default(0);
            $table->tinyInteger('status')->default(10);
            $table->foreignId('parent_id')->nullable()->constrained('taxonomies')->nullOnDelete();
            $table->timestamps();
            $table->unique(['type', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxonomies');
    }
};
