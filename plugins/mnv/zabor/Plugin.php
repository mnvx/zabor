<?php

namespace Mnv\Zabor;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Mnv\Zabor\Components\EditProfile;
use Mnv\Zabor\Components\Profile;
use Mnv\Zabor\Components\WhoWeAre;
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
            'name'        => 'Zabor',
            'description' => 'Components and models for Zabor project',
            'author'      => 'mnv',
            'icon'        => 'icon-rocket'
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
        if ($this->app->environment() === 'local') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            EditProfile::class => 'editProfile',
            Profile::class => 'profile',
            WhoWeAre::class => 'whoweare',
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
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate
    }
}
