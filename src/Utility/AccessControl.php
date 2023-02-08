<?php

namespace App\Utility;

class AccessControl
{

    private static AccessControl $instance;
    public static bool  $hasAdminAccess;
    public static bool $hasSuperAdminAccess;



    private final function __construct() {
//        echo __CLASS__ . " initialize only once ";
    }
    /**
     * @param String $username
     */
    public static function setHasAdminAccess(string $hasAdminAccess): void
    {
        self::$hasAdminAccess = $hasAdminAccess;

    }

    public static function setHasSuperAdminAccess(string $hasSuperAdminAccess): void
    {
        self::$hasSuperAdminAccess = $hasSuperAdminAccess;
    }

    public static function hasAdminRights(){
    }

    public static function getInstance(): AccessControl
    {
        if (!isset(self::$instance)) {
            self::$instance = new AccessControl();
        }

        return self::$instance;
    }
}