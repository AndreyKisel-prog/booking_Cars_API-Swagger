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
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('userId')->unique()->unsigned();
            $table->bigInteger('carId')->unique()->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('userId')
                ->references('id')
                ->on('users');

            $table->foreign('carId')
                ->references('id')
                ->on('cars');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actions');
    }
};
