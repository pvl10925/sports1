<?php
// app/config/db.php hello

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host = 'localhost';     // hoặc localhost
            $port = '3306';          // port phpMyAdmin của bạn
            $db   = 'sports_shop';   // tên database
            $user = 'root';   // user mới bạn tạo
            $pass = '';        // mật khẩu user đó

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

            self::$instance = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return self::$instance;
    }
}
