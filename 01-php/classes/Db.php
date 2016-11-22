<?php
/**
 * Class Db
 *
 * Simple 10-minutes interface to mysql db
 *
 * @author Igor <igor.goroun@gmail.com>
 * @version none
 */

class Db {

    /* mysql connection paramaeters */
    private $host="127.0.0.1";
    private $port=3306;
    private $base=null;
    private $user=null;
    private $pass=null;

    /* connection variable */
    private $conn=null;

    /**
     * Db constructor.
     * @param array $params
     * @throws Exception
     */
    public function __construct($params=null)
    {
        if ($params == null) {
            throw new Exception("Database connection parameters not set");
        }
        $this->setParams($params);

    }

    /**
     * Makes select query from db
     *
     * @param $sql
     * @return mixed
     * @throws Exception
     */
    public function Select($sql) {
        $this->mysqlConnect();
        $result = $this->conn->query($sql);

        if (!$result || $this->conn->errno) {
            throw new Exception("Cannot run query ({$sql}): [{$this->conn->errno}] {$this->conn->error}");
        }
        if (gettype($result) == 'boolean') {
            throw new Exception("This method can run only selectable queries :)");
        }

        if ($result->num_rows === 0) {
            return false;
        }

        return $result->fetch_all(MYSQLI_ASSOC);

    }

    /**
     * Makes a query except select
     *
     * @param $sql
     * @return mixed
     * @throws Exception
     */
    public function Query($sql) {
        /* Get connection */
        $this->mysqlConnect();

        /* Run query */
        $result = $this->conn->query($sql);

        /* check result */
        if (!$result || $this->conn->errno) {
            throw new Exception("Cannot run query ({$sql}): [{$this->conn->errno}] {$this->conn->error}");
        }

        if (gettype($result) == 'boolean') {
            return $result;
        } else {
            throw new Exception("This method cannot run selectable queries :)");
        }
    }

    private function mysqlConnect() {
        if ($this->conn == null) {
            $mysqli = new mysqli($this->host, $this->user, $this->pass, $this->base, $this->port);
            if ($mysqli->connect_errno) {
                throw new Exception("Mysql error: [{$mysqli->connect_errno}] {$mysqli->connect_error}");
            }
            $this->conn = $mysqli;
        }
    }
    public function mysqlDisconnect() {
        if (!$this->conn == null) {
            $this->conn->close();
        }
    }

    private function setParams($params) {
        foreach ($params as $p=>$v) {
            if (property_exists($this,$p)) {
                $this->$p = $v;
            }
        }
    }
}

?>
