<?php namespace Mnv\User;

use Backend;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use October\Rain\Exception\ValidationException;
use RainLab\User\Controllers\Users;
use RainLab\User\Models\User;
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
        Event::listen('clake.ue.preregistration', function() {
            $validator = Validator::make(request()->all(), [
                'consent' => 'required',
            ]);

            if ($validator->fails()) {
                throw new ValidationException([
                    'consent' => Lang::get('mnv.user::lang.components.account.need_accept'),
                ]);
            }
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
