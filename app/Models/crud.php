<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class crud extends Model
{
    use HasFactory;
    public $table = 'crud';
    public $guarded = ['id'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'tipe_crud'
    ];
}
