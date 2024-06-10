<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class telegram_user extends Model
{
    use HasFactory;
    public $table = 'telegram_user';
    public $guarded = ['id'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'telegram_chat_id',
        'menu_id',
        'akses_database_id',
        'crud_id'
    ];
}
