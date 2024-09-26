<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->string('judul_pengajuan');
            $table->string('jenis_QE');
            $table->string('sto');
            $table->text('alamat');
            $table->text('kebutuhan_material');
            $table->string('progress');
            $table->string('detail_QE');
            $table->string('titik_kordinasi')->nullable();
            $table->string('tingkat_urgensi');
            $table->string('pelapor');
            $table->string('no_telepon');
            $table->date('tanggal_pengajuan');
            $table->text('keterangan')->nullable();
            $table->string('evidence_path')->nullable();
            $table->string('surat_pihak_ketiga_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
