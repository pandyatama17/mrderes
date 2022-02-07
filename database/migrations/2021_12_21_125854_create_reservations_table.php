<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('supervisor_id');
            $table->integer('admin_id')->nullable()->default(null);
            $table->datetime('reservation_time');
            $table->integer('duration'); //in minute
            // $table->integer('status')->default(0); //0 = pending, 1 = approved, 2 = started, 3 = declined, 4 = finished/done
            $table->boolean('approved')->default(false);
            $table->datetime('start_time')->nullable()->default(null);
            $table->timestamps();
        });
        Schema::create('reservation_history', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('supervisor_id');
            $table->integer('admin_id');
            $table->datetime('reservation_time');
            $table->integer('duration'); //in minute
            $table->datetime('start_time');
            $table->datetime('finish_time');
            $table->integer('overtime')->default(0); //in minute
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
        Schema::dropIfExists('reservations');
    }
}
