<?php namespace Mnv\User;

use Backend;
use Auth;
use Clake\Userextended\Models\UserExtended;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use October\Rain\Exception\ValidationException;
use RainLab\User\Controllers\Users;
use RainLab\User\Models\User;
use Redirect;
use System\Classes\PluginBase;

/**
 * user Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'user',
            'description' => 'No description provided yet...',
            'author'      => 'mnv',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        // Accept terms of usage
        Event::listen('clake.ue.preregistration', function() {
            $validator = Validator::make(request()->all(), [
                'consent' => 'required',
                'zabor_stead' => 'required',
                'zabor_phone' => 'required',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        });

        // Сохраняем доп поля при регистрации профиля
        Event::listen('clake.ue.settings.create', function($settingsManager) {
            $validator = Validator::make(request()->all(), [
                'zabor_stead' => 'required',
                'zabor_phone' => 'required',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user = $settingsManager->userCheck();
            $user->zabor_stead = request()->get('zabor_stead');
            $user->zabor_phone = request()->get('zabor_phone');
            $user->save();
        });

        // Сохраняем доп оля при редактировании профиля
        Event::listen('clake.ue.settings.update', function($settingsManager) {
            $validator = Validator::make(request()->all(), [
                'surname' => 'required',
                'zabor_stead' => 'required',
                'zabor_phone' => 'required',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user = $settingsManager->userCheck();
            $user->surname = request()->get('surname');
            $user->zabor_stead = request()->get('zabor_stead');
            $user->zabor_phone = request()->get('zabor_phone');
            $user->save();
        });

        // Add more fields to user model
        User::extend(function($model)
        {
            $model->addFillable([
                'zabor_stead',
                'zabor_phone',
                'zabor_about',
                'zabor_webpage',
                'zabor_blog',
                'zabor_facebook',
                'zabor_twitter',
                'zabor_skype',
            ]);
        });

        // Add more fields to user backend form
        Users::extendFormFields(function($form, $model, $context)
        {
            if (!$model instanceof User) {
                return;
            }
            $form->addTabFields([
                'zabor_stead' => [
                    'label' => 'mnv.user::fields.profile.stead',
                    'tab'   => 'mnv.user::fields.profile.tab',
                    'span'  => 'auto'
                ],
                'zabor_phone' => [
                    'label' => 'mnv.user::fields.profile.phone',
                    'tab'   => 'mnv.user::fields.profile.tab',
                    'span'  => 'auto'
                ],
                'zabor_about' => [
                    'label' => 'mnv.user::fields.profile.about',
                    'tab'   => 'mnv.user::fields.profile.tab',
                    'type'  => 'textarea',
                    'size'  => 'small',
                    'span'  => 'full'
                ],
                'zabor_webpage' => [
                    'label' => 'mnv.user::fields.profile.webpage',
                    'tab'   => 'mnv.user::fields.profile.tab',
                    'span'  => 'auto'
                ],
                'zabor_blog' => [
                    'label' => 'mnv.user::fields.profile.blog',
                    'tab'   => 'mnv.user::fields.profile.tab',
                    'span'  => 'auto'
                ],
                'zabor_facebook' => [
                    'label' => 'mnv.user::fields.profile.facebook',
                    'tab'   => 'mnv.user::fields.profile.tab',
                    'span'  => 'auto'
                ],
                'zabor_twitter' => [
                    'label' => 'mnv.user::fields.profile.twitter',
                    'tab'   => 'mnv.user::fields.profile.tab',
                    'span'  => 'auto'
                ],
                'zabor_skype' => [
                    'label' => 'mnv.user::fields.profile.skype',
                    'tab'   => 'mnv.user::fields.profile.tab',
                    'span'  => 'auto'
                ],
            ]);
        });

        // Control permissions with white list of urls
        Event::listen('cms.router.beforeRoute', function($url, $router) {
            $whitelist = [
                '/',
                '/auth/*',
                '/about',
                '/contact',
                '/404',
                '/error',
            ];
            $whitelist = array_map(function($item) { return str_replace('/', '\\/', $item); }, $whitelist);
            $whitelist = array_map(function($item) { return str_replace('*', '.*', $item); }, $whitelist);
            $whitelist = array_map(function($item) { return  '/^' . $item . '$/u'; }, $whitelist);

            foreach ($whitelist as $item) {
                if (preg_match($item, $url) === 1) {
                    return null;
                }
            }

            // Only users with role "member" can access content outside of white list.
            $whiteGroup = 'member';
            $user = Auth::getUser();
            if ($user) {
                $userGroupCodes = $user->groups->pluck('code')->toArray();
                if (in_array($whiteGroup, $userGroupCodes)) {
                    return null;
                }
            }
            return $router->findByUrl('/auth/access-denied');
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Mnv\User\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'mnv.user.some_permission' => [
                'tab' => 'user',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'user' => [
                'label'       => 'user',
                'url'         => Backend::url('mnv/user/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['mnv.user.*'],
                'order'       => 500,
            ],
        ];
    }
}
