<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNamaLopColumnToTicketsTable extends Migration
{
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Add the 'NAMA_LOP' column after the 'id' column
            $table->string('NAMA_LOP', 255)->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Drop the 'NAMA_LOP' column
            $table->dropColumn('NAMA_LOP');
        });
    }
}
