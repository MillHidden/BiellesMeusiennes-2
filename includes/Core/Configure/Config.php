<?php

namespace Core\Configure;

use Core\Orm\Database;
use Core\Orm\QueryBuilder;

/**
 * Class Config
 * @package Core\Configure
 */
Class Config {

    /**
     * @var
     */
    private static $configuration;
    /**
     * @var
     */
    private static $database;
    /**
     * @var
     */
    private static $queryBuilder;

    /**
     * @return mixed
     */
    public static function getConfig() {
        if ( self::$configuration === null ) {
            self::$configuration = json_decode(file_get_contents(__DIR__ ."/config.json"));
        }
        return self::$configuration;
    }

    /**
     * @return Database
     */
    private static function getDb(){
        if ( self::$database === null ) {
            $config = self::getConfig();
            self::$database = new Database($config->database->name, $config->database->user, $config->database->pass, $config->database->host);
        }
        return self::$database;
    }

    /**
     * @return QueryBuilder
     */
    public static function QueryBuilder() {
        if ( self::$queryBuilder === null ){
            $db = self::getDb();
            self::$queryBuilder = new QueryBuilder($db);
        }
        return self::$queryBuilder;
    }

}
