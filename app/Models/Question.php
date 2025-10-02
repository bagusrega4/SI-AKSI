<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['section_id', 'text', 'type', 'max_selections', 'min_selections'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
