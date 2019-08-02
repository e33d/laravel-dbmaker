<?php
/**
 * Created by syscom.
 * User: syscom
 * Date: 17/06/2019
 * Time: 15:50
 */


namespace DBMaker\ODBC;

use PDO;
use Illuminate\Database\Connection;
use DBMaker\ODBC\Schema\DBMakerBuilder as DBMakerSchemaBuilder;
use DBMaker\ODBC\Query\DBMakerBuilder as DBMakerQueryBuilder;
use DBMaker\ODBC\Query\Processors\DBMakerProcessor;
use DBMaker\ODBC\Query\Grammars\DBMakerGrammar as QueryGrammar;
use DBMaker\ODBC\Schema\Grammars\DBMakerGrammar as SchemaGrammar;


class DBMakerConnection extends Connection   
{
	
	/**
	 * The default fetch mode of the connection.
	 *
	 * @var int
	 */
	protected $fetchMode = PDO::FETCH_OBJ;
	
	
	public function insert($query, $bindings = [])
	{	
		$this->beginTransaction();
		try{
			$count = count($bindings);
			for( $i=0 ; $i<$count; $i++){
				$result = $this->statement($query, $bindings[$i]);
			}
			$this->commit();
		}
		catch(Exception $e){
			$this->rollback();
		}
		return $result;
	}
	
	
	/**
	 * Get a new query builder instance.
	 *
	 * @return \Illuminate\Database\Query\Builder
	 */
	public function query()
	{
		return new DBMakerQueryBuilder(
				$this, $this->getQueryGrammar(), $this->getPostProcessor()
		);
	}
	 
	/**
	 * Get the default query grammar instance.
	 *
	 * @return Dbmaker\Odbc\Query\Grammars\DBMakerGrammar
	 */
	protected function getDefaultQueryGrammar()
	{
		return $this->withTablePrefix(new QueryGrammar);
	}
	
	/**
	 * Get a schema builder instance for the connection.
	 *
	 * @return Dbmaker\Odbc\Schema\DBMakerBuilder
	 */
	public function getSchemaBuilder()
	{
		if (is_null($this->schemaGrammar)) {
			$this->useDefaultSchemaGrammar();
		}
	
		return new DBMakerSchemaBuilder($this);
	}
	
	
	/**
	 * Get the default schema grammar instance.
	 *
	 * @return Dbmaker\Odbc\Schema\Grammars\DBMakerGrammar
	 */
	protected function getDefaultSchemaGrammar()
	{
		return $this->withTablePrefix(new SchemaGrammar);
	}
	
	/**
	 * Get the default schema grammar instance.
	 *
	 * @return Dbmaker\Odbc\Schema\Grammars\DBMakerGrammar
	 */
	protected function DefaultSchemaGrammar()
	{
		return $this->withTablePrefix(new SchemaGrammar);
	}
	
	/**
	 * Get the default post processor instance.
	 *
	 * @return Dbmaker\Odbc\Query\Processors\DBMakerProcessor
	 */
	protected function getDefaultPostProcessor()
	{
		return new DBMakerProcessor;
	} 
    
    /**
     * get the dbmaker options.
     *
     * @return void
     */
    public function getDB_IDCap()
    {
    	return $this->getConfig('options.dbidcap');
    }

}
