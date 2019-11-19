<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\SaveMessage;
class Message extends Model
{
    //
    protected $table = "messages";
    public function user()
    {
        return $this->belongsTo('User');
    }
    public function text()
    {
        return $this->hasOne('SaveMessage');
    }
}
