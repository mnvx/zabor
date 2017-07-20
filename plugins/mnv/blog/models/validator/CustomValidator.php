<?php

namespace Mnv\Blog\Models\Validator;

use Mnv\Blog\Models\Catalog\SurveyQuestionType;
use ReflectionMethod;
use ReflectionObject;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class CustomValidator
 * @package Mnv\Blog\Models\Validator
 * @unused По непонятным причинам этот валидатор мешается при обновлении профиля пользователя (пытается преобразоваться в строку).
 */
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

    public static function fail($validator, $parameter, $rule, $parameters)
    {
        $method = new ReflectionMethod($validator, 'addFailure');
        $method->setAccessible(true);
        $reflector = new ReflectionObject($validator);
        $method = $reflector->getMethod('addFailure');
        $method->setAccessible(true);
        $method->invoke($validator, $parameter, $rule, $parameters);
    }

}