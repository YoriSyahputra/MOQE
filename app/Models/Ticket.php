<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'id',
        'judul_pengajuan',
        'jenis_QE',
        'sto',
        'nomer_ticket_insera',
        'alamat',
        'kebutuhan_material',
        'progress',
        'detail_QE',
        'titik_kordinasi',
        'tingkat_urgensi',
        'pelapor',
        'tanggal_pengajuan',
        'keterangan',
        'evidence_path',
        'surat_pihak_ketiga_path',
    ];

    // If you want the id to be treated as a string (since it's not an auto-incrementing integer)
    public $incrementing = false;
    protected $keyType = 'string';
}