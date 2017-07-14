<?php

namespace Mnv\Blog\Models\Validator;

use Mnv\Blog\Models\Catalog\SurveyQuestionType;
use Symfony\Component\Translation\TranslatorInterface;

class CustomValidator extends \Illuminate\Validation\Validator
{
    public function __construct(TranslatorInterface $translator, array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $messages = $messages + [
            'question_required' => 'Вопрос обязателен к заполнению',
            'question_type_required' => 'Тип вопроса обязателен к заполнению',
            'question_type_correct' => 'Некорретный тип вопроса',
        ];

        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
    }

    public function validateQuestionRequired($attribute, $value, $parameters)
    {
        $questions = request()->get('survey', []);
        foreach ($questions as $key => $question) {
            if (empty($question['question'])) {
                $this->addFailure('question', 'question_required', $parameters);
            }
        }
        return true;
    }

    public function validateQuestionTypeRequired($attribute, $value, $parameters)
    {
        $questions = request()->get('survey', []);
        foreach ($questions as $key => $question) {
            if (empty($question['type'])) {
                $this->addFailure('question[' . $key . '][type]', 'question_type_required', $parameters);
            }
        }
        return true;
    }

    public function validateQuestionTypeCorrect($attribute, $value, $parameters)
    {
        $questions = request()->get('survey', []);
        foreach ($questions as $key => $question) {
            if (isset($question['type']) && !in_array($question['type'], SurveyQuestionType::getCodes())) {
                $this->addFailure('question[' . $key . '][type]', 'question_type_correct', $parameters);
            }
        }
        return true;
    }

}