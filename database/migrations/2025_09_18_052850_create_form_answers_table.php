<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('form_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unique(['form_id', 'user_id']);
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_answers');
    }
};
