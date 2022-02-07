<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMeetingRoomToDesksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('desks', function (Blueprint $table) {
            $table->boolean('has_parent')->default(false)->after('zone_id');
            $table->integer('parent_id')->after('has_parent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('desks', function (Blueprint $table) {
            //
        });
    }
}
