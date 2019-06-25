<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoneJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('done_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('connection');
            $table->string('queue');
            $table->string('job');
            $table->longText('payload');
            $table->tinyInteger('attempts')->unsigned();
            $table->dateTime('done_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('done_jobs');
    }
}
