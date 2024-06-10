<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class telegram_menu extends Model
{
    use HasFactory;
    public $table = 'telegram_menu';
    public $guarded = ['id'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'nama_menu'
    ];
}
