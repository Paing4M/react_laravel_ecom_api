<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['session_id', 'status', 'total_price', 'user_id'	];

    protected $with=['user'];

    public function user()
    {
      return $this->belongsTo(User::class , 'user_id');
    }
}
