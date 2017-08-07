<?php

namespace Mnv\Zabor\Components;

use Clake\Userextended\Models\Settings as UserExtendedSettings;
use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Illuminate\Support\Facades\Validator;
use October\Rain\Exception\ValidationException;
use October\Rain\Support\Facades\Flash;
use RainLab\User\Facades\Auth;
use RainLab\User\Models\User;

class EditProfile extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'User Profile Edit',
            'description' => 'Edit profile of user.'
        ];
    }

    public function onRun()
    {
        parent::onRun();

        $this->addJs('/plugins/mnv/zabor/assets/js/electric_meter.js');
        $this->addJs('/modules/system/assets/ui/vendor/mustache/mustache.js');
    }

    public function defineProperties()
    {
        return [
            'basicPage' => [
                'title'       => 'Основная информация',
                'description' => 'Страница редактирования основной информации',
                'type'        => 'dropdown',
                'default'     => 'auth/profile',
                'group'       => 'Links',
            ],
            'passwordPage' => [
                'title'       => 'Страница с паролем',
                'description' => 'Страница редактирования пароля',
                'type'        => 'dropdown',
                'default'     => 'auth/profile-password',
                'group'       => 'Links',
            ],
            'avatarPage' => [
                'title'       => 'Страница с аватаром',
                'description' => 'Страница управления аватаром',
                'type'        => 'dropdown',
                'default'     => 'auth/profile-avatar',
                'group'       => 'Links',
            ],
            'electricMeterPage' => [
                'title'       => 'Страница с электросчётчиками',
                'description' => 'Страница управления счётчиками электроэнергии',
                'type'        => 'dropdown',
                'default'     => 'auth/profile-electric-meter',
                'group'       => 'Links',
            ],
        ];
    }

    public function getBasicPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getPasswordPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getAvatarPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getElectricMeterPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getUserInfo($userId)
    {
        $user = User::find((int)$userId);

        return $user;
    }

    public function onEditProfileBasic()
    {
        $rules = [
            'name' => 'required',
            'surname' => 'required',
            'zabor_stead' => 'required',
            'zabor_phone' => 'required',
        ];
        $validation = Validator::make(post(), $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $user = Auth::getUser();

        $user->name = post('name');
        $user->surname = post('surname');
        $user->zabor_phone = post('zabor_phone');
        $user->zabor_stead = post('zabor_stead');
        $user->timezone_id = post('timezone');

        $user->save();

        Flash::success('Профиль успешно обновлен');
    }

    public function onEditProfilePassword()
    {
        $rules = [
            'password' => UserExtendedSettings::get('validation_password', 'between:4,255|confirmed'),
        ];
        $validation = Validator::make(post(), $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $user = Auth::getUser();

        if (post('password') && post('password_confirmation')) {
            $user->password = post('password');
            $user->password_confirmation = post('password_confirmation');
        }

        $user->save();

        if (post('password')) {
            Auth::login($user->reload(), true);
        }

        Flash::success('Пароль успешно изменен');
    }
}