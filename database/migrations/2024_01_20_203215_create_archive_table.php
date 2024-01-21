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
        Schema::create('archive', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("num");
            $table->string("team");
            $table->string("full");
            $table->string("part");
            $table->integer("right_part")
                ->default(0);
            $table->integer("right_full")
                ->default(0);
            $table->integer("right_withnum")
                ->default(0);
            $table->string("season");  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archive');
    }
};
