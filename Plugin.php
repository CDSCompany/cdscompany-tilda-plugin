<?php namespace Cds\Tilda;

use Backend;
use System\Classes\PluginBase;

/**
 * tilda Plugin Information File
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
            'name'        => 'Tilda Connect',
            'description' => 'Show Tilda pages for your site',
            'author'      => 'CDS Company',
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

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Cds\Tilda\Components\TildaPageDetail' => 'tildaPageDetail',
            'Cds\Tilda\Components\TildaPagesList' => 'tildaPagesList',
            'Cds\Tilda\Components\TildaPagesDetail' => 'tildaPagesDetail',
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
        return [
            'tilda' => [
                'label'       => 'Tilda connect',
                'url'         => Backend::url('cds/tilda/tildaprojects'),
                'icon'        => 'icon-refresh',
                'permissions' => ['cds.tilda.*'],
                'order'       => 500,
            ],
        ];
    }


    public function registerSettings()
    {
        return [
            'settings' =>[
                'label'       => 'Tilda settings',
                'description' => 'API/Connections',
                'category'    => 'Tilda',
                'icon'        => 'icon-cog',
                'class'       => 'Cds\Tilda\Models\Setting',
                'order'       => 500,
                'keywords'    => '',
                'permissions' => ['cds.tilda.access_settings']
            ]

        ];
    }    
}
