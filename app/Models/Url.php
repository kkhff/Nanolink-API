<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['url', 'new_url', 'click'])]
class Url extends Model
{
    public function url_clicks()
    {
        return $this->hasMany(UrlClick::class);
    }
}
