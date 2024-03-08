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
    protected static $table = 'statistics';

    /**
     * The columns of the database table for the user.
     *
     * @var array
     */
    protected static $columns = ['userid', 'age', 'height',  'weight', 'activity_factor', 'strikes'];

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
    public function deleteStats(){
        $queryStats = "DELETE FROM " . self::$table . " WHERE userid = :id";
        $statementStats = self::$db->prepare($queryStats);
        $statementStats->bindValue(":id", self::$userid, PDO::PARAM_INT);
        $successStats = $statementStats->execute();
        return $successStats;
    }
    public function createStats($age){
        $query = "INSERT INTO ".self::$table ."(".self::$columns.") (:userid, :age, :peso, :altura, :actividadFisica, 0)";
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(':userid', $this->userid, PDO::PARAM_INT);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->bindParam(':peso', $this->weight, PDO::PARAM_INT);
        $stmt->bindParam(':altura', $this->height, PDO::PARAM_INT);
        $stmt->bindParam(':actividadFisica', $this->activity_factor, PDO::PARAM_INT);
        $resultado =  $stmt->execute();
        return $resultado;
    }
    public function updateStats($userid, $height, $weight, $activity_factor){
        $query = "UPDATE " . self::$table . " SET weight=:weight, height=:height, activity_factor=:activity_factor WHERE userid=:userid;"; // Corrected parameter name
        $stmt = self::$db->prepare($query);
        $stmt->bindParam(':userid',  $userid, PDO::PARAM_INT);
        $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);
        $stmt->bindParam(':height', $height, PDO::PARAM_INT);
        $stmt->bindParam(':activity_factor', $activity_factor, PDO::PARAM_INT);
        $resultado = $stmt->execute();
        error_log("Success Data: id:".$userid." height:".$height." weight:". $weight." af:". $activity_factor."\n", 3, './../errorLog/error.log');
        return $resultado;
    }
    public function getStrikes(){
        $query = "SELECT strikes FROM " . self::$table . " WHERE userid = :userid;";
        $statement = self::$db->prepare($query);
        $statement->bindParam(':userid', $this->userid, PDO::PARAM_INT);
        $resultado = $statement->execute();
        return $resultado;
    }
    /**
     * Gets the statistics of the user
     * 
     * @param id $user_id The id of the user
     */
    public static function loadStatisticData($id)
    {
        $queryStatistics = "SELECT * from statistics where userid=:id";
        $statementStatistics = self::$db->prepare($queryStatistics);
        $statementStatistics->bindValue(":id", $id);
        $statementStatistics->execute();
        $rowS = $statementStatistics->fetch(PDO::FETCH_ASSOC);
        return $rowS;
    }
}
?>