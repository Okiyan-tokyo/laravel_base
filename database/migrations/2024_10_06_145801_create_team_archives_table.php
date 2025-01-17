<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_archives', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("jpn_name");
            $table->integer("red");
            $table->integer("blue");
            $table->integer("green");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_archives');
    }
};
