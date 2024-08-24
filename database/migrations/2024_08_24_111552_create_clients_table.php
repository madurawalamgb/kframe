<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('clients');
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
$table->text('qualification');
$table->integer('age');
$table->boolean('higher_than')->default(false);
$table->enum('gender',['F','M']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
};