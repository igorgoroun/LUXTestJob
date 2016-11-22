<?php
/**
 * Test job for vacancy
 *
 * Vacancy decription:
 * http://rabota.ua/company239121/vacancy6463561
 *
 * Test description:
 * https://docs.google.com/document/d/15l2gZrQlMJg4OQthJ7eJaujdbxi4LbR58Vggd2z8H9A/edit#
 *
 * @author Igor <igor.goroun@gmail.com>
 * @version 0.2
 */

final class Init {

    private $db=null;
    private $tbl="test";

    /**
     * Init constructor.
     * @param $db
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Table creation
     *
     * I've got a simplest way - drop table before it's created,
     * but the better way is to check is table exists
     *
     * @param bool $force
     */
    private function create ($force=false) {
        /* drop table before, if force is true */
        if ($force) {
            $drop_query = "drop table if exists `{$this->tbl}`";
            try {
                $this->db->Query($drop_query);
            } catch (Exception $e) {
                print $e->getMessage();
            }
            /* create table prefix */
            $query = "create table ";
        /* or start query with table existance chech */
        } else {
            $query = "create table if not exists ";
        }

        /* table creation query */
        $query .= "`{$this->tbl}` (
          `id` int(11) not null auto_increment,
          `script_name` varchar(25) default NULL,
          `start_time` timestamp default '0000-00-00 00:00:00',
          `end_time` timestamp default '0000-00-00 00:00:00',
          `result` set('normal','illegal','failed','success') not NULL,
          primary key (`id`),
          KEY `res_ind` (`result`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";


        /* try to create table or catch exception */
        try {
            $this->db->Query($query);
            printf("Table %s successfully created\n",$this->tbl);
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    /**
     * Table filler
     *
     * Fills table with random data:
     * script_name field fills with list of scripts in /usr/bin
     * end_time is always bigger than start_time
     * result is one of ['normal','illegal','failed','success']
     *
     * @param int $lines
     * @return void
     */
    private function fill ($lines=100) {
        /* get values for field `script_name` (a list of scripts in /usr/bin dir) */
        $scriptsList = scandir("/usr/bin");

        /* default time value */
        $maxBackTime = time();

        /* available values for field `result` */
        $resultVariants = ['normal','illegal','failed','success'];

        /* array for query values */
        $queryValues = [];

        /* create data for insert */
        $i=0;
        while ($i<$lines) {
            $script_name = $scriptsList[mt_rand(0,count($scriptsList)-1)]; // random value from scripts list
            $start_time = $maxBackTime-mt_rand(0,$maxBackTime); // random time
            $end_time = mt_rand($start_time,$maxBackTime); // random time that more than start_time
            $result = $resultVariants[mt_rand(0,count($resultVariants)-1)]; // random result from array
            /* add values to array */
            $queryValues[] = "('{$script_name}','".date('Y-m-d H:i:s',$start_time)."','".date('Y-m-d H:i:s',$end_time)."','{$result}')";

            $i++;
        }

        /* create final insert query */
        $query = "INSERT into {$this->tbl} (script_name,start_time,end_time,result) values ".implode(',',$queryValues);

        /* try to execute query */
        try {
            $this->db->Query($query);
            //printf("%d rows added to table %s\n",$i,$this->tbl); // debug info
        } catch (Exception $e) {
            print $e->getMessage();
        }

    }

    /**
     * Get data
     *
     * Select all data from table with results 'normal' or 'success'
     * prints formatted data to stdout
     *
     * @param bool $print
     * @return mixed
     */
    public function get($print=false) {
        /* query for selecting all data for defined results */
        $query = "select
                id,script_name,start_time,end_time,result
              from {$this->tbl}
              where result in ('normal','success')";

        /* try to execute query */
        try {
            $data = $this->db->Select($query);
        } catch (Exception $e) {
            print $e->getMessage();
        }

        /* format data and print to stdout if $print is true */
        if ($print) {
            foreach ($data as $record) {
                vprintf("%' 11d %' 25s %' 20s %' 20s  %s\n", $record);
            }
        } else {
            return $data;
        }

    }

    /**
     * Create table public
     *
     * Temporary method for table creation testing
     */
    public function newtable($force=false) {
        $this->create($force);
    }

    /**
     * Fill table public
     *
     * Temporary method for table filling testing
     */
    public function filltable($lines=100) {
        $this->fill($lines);
    }
}

?>