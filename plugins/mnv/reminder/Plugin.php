<?php namespace Mnv\Reminder;

use Mnv\Reminder\Components\Reminder;
use Mnv\Reminder\Models\NewsRead;
use RainLab\Blog\Components\Post;
use System\Classes\PluginBase;

/**
 * reminder Plugin Information File
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
            'name'        => 'Reminder',
            'description' => 'Remind for new posts and comments',
            'author'      => 'mnv',
            'icon'        => 'icon-bell'
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
        Post::extend(function($component) {
            $component->bindEvent('component.run', function() use ($component) {
                if (isset($component->post)) {
                    NewsRead::markPostAsRead($component->post->id);
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
        return [
            Reminder::class => 'reminder',
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
            'mnv.reminder.some_permission' => [
                'tab' => 'reminder',
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
            'reminder' => [
                'label'       => 'reminder',
                'url'         => Backend::url('mnv/reminder/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['mnv.reminder.*'],
                'order'       => 500,
            ],
        ];
    }
}
