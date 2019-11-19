<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Message;

class SaveMessage extends Model
{
    //
    protected $table = "savemessages";
    public function message()
    {
        return $this->belongsTo('Message');
    }
}
