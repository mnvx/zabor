<?php

namespace Mnv\Blog\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mnv\Blog\Models\Catalog\SurveyQuestionType;
use Mnv\Blog\Models\SurveyAnswer;
use Mnv\Blog\Models\SurveyQuestion;
use October\Rain\Exception\ValidationException;
use RainLab\User\Facades\Auth;

class Survey extends ComponentBase
{
    protected $blogPostPrefixUrl = 'blog/post/';

    public function componentDetails()
    {
        return [
            'name' => 'mnv.blog::lang.survey.name',
            'description' => 'mnv.blog::lang.survey.description'
        ];
    }

    public function onRun()
    {
        parent::onRun();

        $this->addCss('/plugins/mnv/blog/assets/css/survey.css');
        $this->addJs('/plugins/mnv/blog/assets/js/survey.js');
        $this->addJs('/modules/system/assets/ui/vendor/mustache/mustache.js');
    }

    protected function createPassValidator($data)
    {
        $rules = [
            'post_id' => 'required|int|exists:mnv_survey_questions,post_id',
        ];
        $postId = isset($data['post_id']) ? $data['post_id'] : null;
        $surveyQuestions = SurveyQuestion::where('post_id', '=', $postId)->get();
        foreach ($surveyQuestions as $key => $question) {
            $rules['survey_answer[' . $question->id . ']'] = 'required';
        }

        $dataAnswers = isset($data['survey_answer']) ? $data['survey_answer'] : [];
        $answers = [
            'post_id' => $postId,
        ];
        foreach ($dataAnswers as $key => $value) {
            $answers['survey_answer[' . $key . ']'] = $value;
        }
        return Validator::make($answers, $rules);
    }

    public function onPass()
    {
        $data = post();

        $validator = $this->createPassValidator($data);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $userId = Auth::getUser()->id;
        $questionClass = new SurveyQuestion();
        $answerClass = new SurveyAnswer();
        $hasAnswers = SurveyAnswer
            ::join($questionClass->getTable() . ' as q', 'q.id', '=', $answerClass->getTable() . '.question_id')
            ->where('post_id', '=', $data['post_id'])
            ->where('front_user_id', '=' , $userId)->first() !== null;
        if ($hasAnswers) {
            throw new \Exception('Голосовать можно только один раз');
        }

        DB::beginTransaction();

        try {
            $answers = [];
            foreach ($data['survey_answer'] as $key => $value) {
                $answer = [
                    'front_user_id' => $userId,
                    'question_id' => $key,
                    'value' => $value,
                    'booleanValue' => null,
                    'stringValue' => null,
                    'integerValue' => null,
                    'decimalValue' => null,
                    'created_at' => new \DateTime(),
                ];

                $questionTypeId = SurveyQuestion::find($key)->type;
                $questionCode = SurveyQuestionType::getCodeById($questionTypeId);
                if ($questionCode === SurveyQuestionType::QUESTION_TYPE_BOOLEAN) {
                    $answer['booleanValue'] = $value;
                }
                elseif ($questionCode === SurveyQuestionType::QUESTION_TYPE_STRING) {
                    $answer['stringValue'] = $value;
                }
                elseif ($questionCode === SurveyQuestionType::QUESTION_TYPE_SELECT) {
                    $answer['integerValue'] = $value;
                }

                $answers[] = $answer;
            }
            SurveyAnswer::insert($answers);

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        return redirect(url($this->controller->currentPageUrl()));
    }
}