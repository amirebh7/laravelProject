<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveCode extends Model
{
    use HasFactory;
//    protected $table = 'active_code';
    protected $fillable = [
        'user_id','code','expired_at'
    ];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);
    }
}
