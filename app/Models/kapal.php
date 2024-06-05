<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kapal extends Model
{
    use HasFactory;
    public $table = 'kapal';
    public $guarded = ['id'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'nama_kapal'
    ];

}
