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
        Schema::create('person_skill', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('skill_id');


            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('people')->cascadeOnDelete();
            $table->foreign('skill_id')->references('id')->on('skills')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_skill');
    }
};
