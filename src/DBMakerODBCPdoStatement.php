<?php
/**
 * Created by syscom.
 * User: syscom
 * Date: 17/06/2019
 * Time: 15:50
 */


namespace Dbmaker\Odbc;

use PDOStatement;

class DBMakerODBCPdoStatement extends PDOStatement
{
    protected $query;
    protected $params = [];
    protected $statement;

    /**
     *
     * @param  string  $conn
     * @param  string  $query
     * @return Dbmaker\Odbc\DBMakerPdo
     */
    public function __construct($conn, $query)
    {
    	echo "DBMakerODBCPdoStatement<br>";
        $this->query = preg_replace('/(?<=\s|^):[^\s:]++/um', '?', $query);
        $this->params = $this->getParamsFromQuery($query);
        $this->statement = odbc_prepare($conn, $this->query);
    }
    /**
     * get Params From Query String
     *
     * @param  string  $qry
     * @return array
     */
    protected function getParamsFromQuery($query)
    {echo $query;
        $params = [];
        $qryArray = explode(" ", $query);
        $i = 0;
        while (isset($qryArray[$i])) {
            if (preg_match("/^:/", $qryArray[$i]))
                $params[$qryArray[$i]] = null;
            $i++;
          }
        return $params;
    }

    /**
     *
     * @return int
     */
    public function rowCount()
    {
        return odbc_num_rows($this->statement);
    }

    /**
     *
     * @param  string  $param
     * @param  string  $val
     * @param  string  $ignore
     * @return void
     */
    public function bindValue($param, $val, $ignore = null)
    {
        $this->params[$param] = $val;
    }

    /**
     *
     * @param  array  $ignore
     * @return void
     */
    public function execute($ignore = null)
    {
        odbc_execute($this->statement, $this->params);
        $this->params = [];
    }

    
    
    public function fetchAll($how = NULL, $class_name = NULL, $ctor_args = NULL)
    {
        $records = [];
        while ($record = $this->fetch()) {
            $records[] = $record;
          }
        return $records;
    }

    /**
     * Fetch an associative array from an ODBC query.
     *
     * @param  array  $option
     * @param  array  $ignore
     * @param  array  $ignore2
     * @return array
     */
    public function fetch($option = null, $ignore = null, $ignore2 = null)
    {
        return odbc_fetch_object($this->statement); 
    }
     
    
}
