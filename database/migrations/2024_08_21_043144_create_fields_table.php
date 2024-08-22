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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms');
            $table->string('field');
            $table->string('name');
            $table->string('description')->nullable();
            $table->enum('type',['TEXT','NUMBER','TEXTAREA','BELONGSTO','BELONGSTOMANY','SELECTON','FUNCTION','HASONE','MULTYSELECTON','BOOLEAN','BUTTON'])->default('TEXT');
            $table->string('selections')->default('[]');
            $table->boolean('readonly')->default(false);
            $table->boolean('disabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
