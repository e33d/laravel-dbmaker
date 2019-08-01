<?php
/**
 * Created by syscom.
 * User: syscom
 * Date: 17/06/2019
 * Time: 15:50
 */


namespace DBMaker\ODBC;

use DBMaker\ODBC\Connectors\DBMakerConnector;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class DBMakerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    	 
        $this->app->resolving('db', function ($db) {
            /** @var DatabaseManager $db */
            $db->extend('odbc', function ($config, $name) {
            	
                $pdoConnection = (new DBMakerConnector())->connect($config);
                $connection = new DBMakerConnection($pdoConnection, $config['database'], isset($config['prefix']) ? $config['prefix'] : '', $config);
                return $connection;
            });
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Model::setConnectionResolver($this->app['db']);
        Model::setEventDispatcher($this->app['events']);
    }
}
