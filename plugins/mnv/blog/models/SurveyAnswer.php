<?php

namespace Mnv\Blog\Models;

use App\Helpers\Date;
use Model;

class SurveyAnswer extends Model
{

    public $table = 'mnv_survey_answers';

    public $timestamps = true;

    public $belongsTo = [
        'front_user' => ['RainLab\User\Models\User']
    ];

    public function getCreatedAtAttribute()
    {
        return Date::translateToDefaultTimezone($this->attributes['created_at']);
    }

}