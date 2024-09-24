<?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   return new class extends Migration
   {
       public function up()
       {
           Schema::table('tickets', function (Blueprint $table) {
               $table->string('nomer_ticket_insera')->nullable()->after('id');
           });
       }

       public function down()
       {
           Schema::table('tickets', function (Blueprint $table) {
               $table->dropColumn('nomer_ticket_insera');
           });
       }
   };