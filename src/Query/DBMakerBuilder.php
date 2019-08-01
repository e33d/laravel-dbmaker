<?php
/**
 * Created by PhpStorm.
 * User: Andrea
 * Date: 21/03/2018
 * Time: 16:30
 */

namespace DBMaker\ODBC\Query;

use App\BaseModel;

use Closure;
use RuntimeException;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use DBMaker\ODBC\Query\Grammars\DBMakerGrammar as DBMakerQueryGrammar;

class DBMakerBuilder extends Builder
{
	/**
	 * @var DB_IDCap
	 */
	private $DB_IDCap;
    public function __construct(ConnectionInterface $connection,
    									  DBMakerQueryGrammar $grammar = null,
    									  Processor $processor = null)
    {

    	$this->DB_IDCap = $connection->getDB_IDCap();
    	return parent::__construct($connection, $grammar, $processor);
    }
    
    /**
     * Insert a new record into the database.
     *
     * @param  array  $values
     * @return bool
     */
    public function insert(array $values)
    {
    	// Since every insert gets treated like a batch insert, we will make sure the
    	// bindings are structured in a way that is convenient when building these
    	// inserts statements by verifying these elements are actually an array.
    	if (empty($values)) {
    		return true;
    	}
    
    	if (! is_array(reset($values))) {
    		$values = [$values];
    	}
    
    	// Here, we will sort the insert keys for every record so that each insert is
    	// in the same order for the record. We need to make sure this is the case
    	// so there are not any errors or problems when inserting these records.
    	else {
    		foreach ($values as $key => $value) {
    			ksort($value);
    
    			$values[$key] = $value;
    		}
    	}
    	
    	
    	$a=$values;
    	$b=count($values[0]);
    	$c=0;
    	// Finally, we will run this query against the database connection and return
    	// the results. We will need to also flatten these bindings before running
    	// the query so they are all in one huge, flattened array for execution.
    	return $this->connection->insert(
    			$this->grammar->compileInsert($this, $values),
    			//$this->cleanBindings($values)
    			$this->cleanBindings(array_chunk(Arr::flatten($values, 1), count($values[0])))
    			//$this->cleanBindings(Arr::flatten($values, 2))
    			
    	);
    	
    
    	 
    	
    }
    
    

    /**
     * Chunk the results of a query by comparing numeric IDs.
     *
     * @param  int  $count
     * @param  callable  $callback
     * @param  string  $column
     * @param  string|null  $alias
     * @return bool
     */
    public function chunkById($count, callable $callback, $column = 'id', $alias = null)
    {
    	$column = strtoupper($column);
    	$alias = $alias ?: $column;
    	$lastId = null; 
    
    	do {
    		$clone = clone $this;
    
    		$results = $clone->forPageAfterId($count, $lastId, $column)->get();
    
    		$countResults = $results->count();
    
    		if ($countResults == 0) {
    			break;
    		}
    
    		if ($callback($results) === false) {
    			return false;
    		}
    
    		$lastId = $results->last()->{$alias};
    
    		unset($results);
    	} while ($countResults == $count);
    
    	return true; //test
    }
 
    /**
     * Get an array with the values of a given column.
     *
     * @param  string  $column
     * @param  string|null  $key
     * @return \Illuminate\Support\Collection
     */
    public function pluck($column, $key = null)
    {
    	if($this->DB_IDCap === 0){
    
    	}else{
    		if(!empty($column))
    			$column = strtoupper($column);
    		if(!empty($key))
    			$key = strtoupper($key);
    	}

    	$queryResult = $this->onceWithColumns(
    			is_null($key) ? [$column] : [$column, $key],
    			function () {
    				return $this->processor->processSelect(
    						$this, $this->runSelect()
    				);
    			}
    	);
    	if (empty($queryResult)) {
    		return collect();
    	}

    	$column = $this->stripTableForPluck($column);
    
    	$key = $this->stripTableForPluck($key);
    
    	return is_array($queryResult[0])
    	? $this->pluckFromArrayColumn($queryResult, $column, $key)
    	: $this->pluckFromObjectColumn($queryResult, $column, $key);
    }
    
    
    /**
     * Execute a query for a single record by ID.
     *
     * @param  int    $id
     * @param  array  $columns
     * @return mixed|static
     */
    public function find($id, $columns = ['*'])
    {
    	if($this->DB_IDCap === 0){
    		$idName = 'id';
    	}else{
    		$idName = 'ID';
    	}
    	return $this->where($idName, '=', $id)->first($columns);
    }
    
    /**
     * Put the query's results in random order.
     *
     * @param  string  $seed
     * @return $this
     */
    public function inRandomOrder($seed = '')
    { 
    	return $this;
    }
   
    /**
     * Insert a new record and get the value of the primary key.
     *
     * @param  array  $values
     * @param  string|null  $sequence
     * @return int
     */
    public function insertGetId(array $values, $sequence = null)
    {
    	$sql = $this->grammar->compileInsertGetId($this, $values, $sequence);
    
    	$values = $this->cleanBindings($values);
    
    	return $this->processor->processInsertGetId($this, $sql, $values, $sequence);
    }
}
