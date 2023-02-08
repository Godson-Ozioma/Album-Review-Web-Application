<?php

namespace App\Utility;

use Symfony\Bundle\MakerBundle\Str;

class Authenticator
{
    private static Authenticator $instance;
    public static String $username;
    public static String $userID;



    private final function __construct() {
//        echo __CLASS__ . " initialize only once ";
    }
    /**
 * @param String $username
 */public static function setUsername(string $username): void
{
    self::$username = $username;
}

    public static function getInstance(): Authenticator
    {
        if (!isset(self::$instance)) {
            self::$instance = new Authenticator();
        }

        return self::$instance;
    }

}