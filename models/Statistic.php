<?php

namespace Model;

namespace Model;

use PDO;
use PDOException;
use DateTime;


class Statistic extends ActiveRecord
{
    /**
     * The database table for the user.
     *
     * @var string
     */
    protected static $tabla = 'statistics';

    /**
     * The columns of the database table for the user.
     *
     * @var array
     */
    protected static $columnasDB = ['userid', 'height', 'weight', 'activity_factor', 'strikes'];

    /**
     * The ID of the user.
     *
     * @var int|null
     */
    private $userid;

    /**
     * The height of the user.
     *
     * @var int|null
     */
    private $height;

    /**
     * The weight of the user.
     *
     * @var int|null
     */
    private $weight;

    /**
     * The activity factor of the user.
     *
     * @var int|null
     */
    private $activity_factor;
    /**
     * The amount of strikes the user have.
     *
     * @var int|null
     */
    private $strikes;


    /**
     * User constructor.
     *
     * @param array $args An associative array containing user data.
     */
    public function __construct($args = [])
    {
        // Initialize properties from the provided array or set them to default values
        $this->userid = $args['id'] ?? null;
        $this->height = $args['height'] ?? null;
        $this->weight = $args['weight'] ?? null;
        $this->activity_factor = $args['activity_factor'] ?? null;
        $this->strikes = $args['strikes'] ?? null;
    }


    /**
     * Stablish a connection to the database
     * 
     * @param PDO $pdo A PDO object representing a connection to the database.
     */
    public static function setDB($database)
    {
        self::$db = $database;
    }
}
