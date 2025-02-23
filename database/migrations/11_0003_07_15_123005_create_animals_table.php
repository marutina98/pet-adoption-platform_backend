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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->integer('sex');
            $table->integer('coat_length');
            $table->date('birthdate');
            $table->foreignId('pet_agency_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_adopter_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('animal_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('animal_breed_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('animal_coat_color_id')->constrained()->onDelete('cascade');
            $table->foreignId('status_id')->default(1)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
