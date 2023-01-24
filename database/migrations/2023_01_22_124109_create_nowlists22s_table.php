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
        Schema::create('nowlists22s', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("num");
            $table->string("team");
            $table->string("full");
            $table->string("part");
        });
    }

    /**
     * Reverse then migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nowlists22s');
    }
};
