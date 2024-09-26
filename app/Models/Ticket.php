<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'NAMA_LOP',
        'judul_pengajuan',
        'jenis_QE',
        'detail_QE',
        'sto',
        'alamat',
        'kebutuhan_material',
        'progress', 
        'titik_kordinasi',
        'tingkat_urgensi',
        'pelapor',
        'no_telepon',
        'tanggal_pengajuan',
        'keterangan',
        'evidence_path',
        'surat_pihak_ketiga_path',
        'nomer_ticket_insera',

    ];

    // If you're using created_at and updated_at timestamps, you can keep this:
    // protected $timestamps = true;

    // If you're not using default incrementing IDs, add this:
    public $incrementing = false;
    protected $keyType = 'string';
}