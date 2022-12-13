<?php
/**
 * @author xpavel39@stud.fit.vutbr.cz
 * Database setup
 */
    // Create connection with database
    $pdo;
    try{
        $pdo = new PDO("mysql:host=localhost;dbname=xsikul15;port=/var/run/mysql/mysql.sock;charset=utf8mb4", 'xsikul15', '6ikinjar');
    } catch (PDOException $e) {
        echo "Connection error: ".$e->getMessage();
        die();
    }

    /***
     * Get the existing PDO object
     */
    function get_pdo()
    {
        global $pdo;
        return $pdo;
    }

?>