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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('testimonial_type'); // 'spa', 'gym', 'yoga'
            $table->unsignedBigInteger('testimonial_id'); // id of spa/gym/yoga
            $table->string('name'); // Customer name (can be different from user name)
            $table->integer('rating')->unsigned()->default(5); // 1-5 stars
            $table->text('comment');
            $table->string('service')->nullable(); // Which service they used
            $table->boolean('is_approved')->default(false); // Admin moderation
            $table->timestamps();

            // Index for faster queries
            $table->index(['testimonial_type', 'testimonial_id', 'is_approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
