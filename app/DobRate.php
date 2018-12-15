<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DobRate extends Model
{
    //
    protected $fillable = [
        'dob', 'rate', 'counts'
    ];

    public function getViewDateAttribute() {
        return \Carbon\Carbon::parse($this->dob)->format('jS F Y');
    }

}
