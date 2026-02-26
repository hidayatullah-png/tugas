<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'idbarang';
    public $incrementing = false; // Jika primary key bukan auto-increment
    protected $keyType = 'string'; // Jika primary key bertipe string
    public $timestamps = false;
    protected $fillable = [
        'nama',
        'harga',
    ];
}
