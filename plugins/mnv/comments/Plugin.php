<?php namespace Mnv\Comments;

use Backend;
use System\Classes\PluginBase;
use Taras\Comments\Models\Comments;

/**
 * comments Plugin Information File
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
            'name'        => 'comments',
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
        // Local event hook that affects all users
        Comments::extend(function($model) {
            $model->bindEvent('model.getAttribute', function($attribute, $value) use ($model) {
                if ($attribute == 'avatar') {
                    $imageUrl = $model->user && $model->user->avatar
                        ? $model->user->avatar->path
                        : url('/plugins/clake/userextended/assets/img/default_user.png');
                    return '<img src="' . $imageUrl . '">';
                }
            });
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
            'Mnv\Comments\Components\MyComponent' => 'myComponent',
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
            'mnv.comments.some_permission' => [
                'tab' => 'comments',
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
            'comments' => [
                'label'       => 'comments',
                'url'         => Backend::url('mnv/comments/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['mnv.comments.*'],
                'order'       => 500,
            ],
        ];
    }
}
