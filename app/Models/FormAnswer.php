<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormAnswer extends Model
{
    protected $fillable = ['form_id', 'user_id'];

    public function details()
    {
        return $this->hasMany(AnswerDetail::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
