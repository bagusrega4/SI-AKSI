<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerDetail extends Model
{
    protected $fillable = [
        'form_answer_id',
        'question_id',
        'section_id',
        'answer_text'
    ];

    public function formAnswer()
    {
        return $this->belongsTo(FormAnswer::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
