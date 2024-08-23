<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('reaches');
        Schema::create('reaches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
$table->integer('count');
$table->text('description');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reaches');
    }
};