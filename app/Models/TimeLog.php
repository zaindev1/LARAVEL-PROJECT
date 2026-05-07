<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    protected $fillable = ['user_id', 'login_at', 'logout_at', 'total_seconds', 'log_date'];

    // Ye bata raha hai ke ye log kis user ka hai
    public function user() {
        return $this->belongsTo(User::class);
    }
}