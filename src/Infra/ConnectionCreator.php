<?php

namespace PSobucki\Auction\Infra;

use PDO;

class ConnectionCreator
{
    private static PDO $pdo;

    public static function getConnection(): PDO
    {
        if (!isset(self::$pdo)) {
            $databasePath = __DIR__ . '/../../db.sqlite';
            self::$pdo = new PDO('sqlite:' . $databasePath);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$pdo;
    }
}
