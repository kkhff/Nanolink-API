<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['url_id', 'ip_address', 'user_agent'])]
class UrlClick extends Model
{
    public function url()
    {
        return $this->belongsTo(Url::class);
    }
}
