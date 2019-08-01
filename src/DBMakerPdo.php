<?php
/**
 * Created by syscom.
 * User: syscom
 * Date: 17/06/2019
 * Time: 15:50
 */


namespace DBMaker\ODBC;


use PDO;
use Exception;
use DBMaker\ODBC\DBMakerPdoStatement as DBMakerPdoStatement;

class DBMakerPdo extends PDO
{
	protected $pdo;
	
	/**
	 * Get the column listing for a given table.
	 *
	 * @param  string  $dsn
	 * @param  string  $username
	 * @param  string  $passwd
	 * @param  array   $options
	 * @return array
	 */
	public function __construct($dsn, $username, $passwd, $options = [])
	{
		parent :: __construct($dsn, $username, $passwd, $options );
	   $pdo = new PDO($dsn, $username, $passwd, $options);
	   $this->setConnection( $pdo );
	 
	}
	

	/**
	 *
	 * @param  string  $statement
	 * @param  array  $driver_options
	 * @return Dbmaker\Odbc\DBMakerPdo
	 */		
	public function prepare($statement, $driver_options = null)
	{
		return parent::prepare($statement  ); 
	}

	
	  /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->pdo;
    }

    /**
     * @param mixed $connection
     * @return void
     */
    public function setConnection($pdo): void
    {
        $this->pdo = $pdo;
    }
}
