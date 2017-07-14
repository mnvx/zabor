<?php

namespace Mnv\Blog\Models\Catalog;

class SurveyQuestionType extends CatalogAbstract
{

    const QUESTION_TYPE_BOOLEAN = 'boolean';
    const QUESTION_TYPE_STRING = 'string';
    const QUESTION_TYPE_SELECT = 'select';
    const QUESTION_TYPE_MULTISELECT = 'multiselect';

    const VALUES = [
        self::QUESTION_TYPE_BOOLEAN => 1,
        self::QUESTION_TYPE_STRING => 2,
        self::QUESTION_TYPE_SELECT => 3,
        self::QUESTION_TYPE_MULTISELECT => 4,
    ];

}