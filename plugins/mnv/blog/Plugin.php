<?php namespace Mnv\Blog;

use Backend;
use Mnv\Blog\Models\Validator\CustomValidator;
use Illuminate\Support\Facades\Validator;
use RainLab\Blog\Models\Post;
use System\Classes\PluginBase;

/**
 * blog Plugin Information File
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
            'name'        => 'mnv.blog::lang.plugin.name',
            'description' => 'mnv.blog::lang.plugin.description',
            'author'      => 'mnv',
            'icon'        => 'icon-pencil',
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
        Post::extend(function($model) {
            $model->attachMany['featured_images']['public'] = false;
        });

        Validator::resolver(function($translator, $data, $rules, $messages, $customAttributes) {
            return new CustomValidator($translator, $data, $rules, $messages, $customAttributes);
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        //return []; // Remove this line to activate

        return [
            'Mnv\Blog\Components\Survey' => 'survey',
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
            'mnv.blog.some_permission' => [
                'tab' => 'blog',
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
            'blog' => [
                'label'       => 'blog',
                'url'         => Backend::url('mnv/blog/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['mnv.blog.*'],
                'order'       => 500,
            ],
        ];
    }
}
