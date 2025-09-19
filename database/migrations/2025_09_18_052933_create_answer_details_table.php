<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('answer_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_answer_id');
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('question_id');
            $table->text('answer_text')->nullable();
            $table->timestamps();

            $table->foreign('form_answer_id')->references('id')->on('form_answers')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answer_details');
    }
};
