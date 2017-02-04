<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIpEmailRfamStateToDcaTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dca_tasks', function (Blueprint $table) {
            $table->char('ip', 15);
            $table->string('email');
            $table->string('rfam');
            $table->string('state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dca_tasks', function (Blueprint $table) {
            $table->dropColumn(['ip', 'email', 'rfam', 'state']);
        });
    }
}
