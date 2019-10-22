<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOceanHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ocean_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('ocean_id');
            $table->string('transparency');
            $table->mediumText('raw_html');
            $table->timestamps();
            $table->foreign('ocean_id')
                ->references('id')
                ->on('oceans')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ocean_histories');
    }
}
