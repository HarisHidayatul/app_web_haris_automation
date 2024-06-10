<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class akses_database extends Model
{
    use HasFactory;
    public $table = 'akses_database';
    public $guarded = ['id'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'nama_database'
    ];
}
