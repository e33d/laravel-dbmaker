<?php
/**
 * Created by syscom.
 * User: syscom
 * Date: 17/06/2019
 * Time: 15:50
 */


namespace DBMaker\ODBC\Query\Grammars;

use RuntimeException;
use Illuminate\Support\Arr;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JsonExpression;
use Illuminate\Database\Query\Grammars\Grammar as Grammar;

class DBMakerGrammar extends Grammar
{
     
    /**
     * Compile the random statement into SQL.
      *
     * @param  string  $seed
     * @return string
     */
    public function compileRandom($seed)
    {
        return 'RAND('.$seed.')';
    }
     
    /**
     * Wrap a single string in keyword identifiers.
       *
     * @param  string  $value
     * @return string
     */
    protected function wrapValue($value)
    {
        return $value === '*' ? $value : ''.str_replace('`', '``', $value).'';
    }
    
    /**
     * Compile an insert statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $values
     * @return string
     */
    public function compileInsert(Builder $query, array $values)
    {
    	// Essentially we will force every insert to be treated as a batch insert which
    	// simply makes creating the SQL easier for us since we can utilize the same
    	// basic routine regardless of an amount of records given to us to insert.
    	$table = $this->wrapTable($query->from);
    
    	if (! is_array(reset($values))) {
    		
    		$values = [$values];
    		
    	}
    
    	$columns = $this->columnize(array_keys(reset($values)));
    	
    	$parameters = '('.$this->parameterize($values[0]).')';	
    	
    	return "insert into $table ($columns) values $parameters";
    }
    
    /**
     * Compile an aggregated select clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $aggregate
     * @return string
     */
    protected function compileAggregate(Builder $query, $aggregate)
    {
    	$column = $this->columnize($aggregate['columns']);
    
    	// If the query has a "distinct" constraint and we're not asking for all columns
    	// we need to prepend "distinct" onto the column name so that the query takes
    	// it into account when it performs the aggregating operations on the data.
    	if ($query->distinct && $column !== '*') {
    		$column = 'distinct '.$column;
    	}
    
    	return 'select '.$aggregate['function'].'('.$column.') as "aggregate"';
    }
    
    /**
     * Compile a "where date" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereDate(Builder $query, $where)
    {
    	return $this->dateBasedWhere('', $query, $where);
    }
    
    /**
     * Compile a "where time" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereTime(Builder $query, $where)
    {
    	return $this->dateBasedWhere('', $query, $where);
    }
    
    /**
     * Compile a "where day" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereDay(Builder $query, $where)
    {
    	return $this->dateBasedWhere('DAYOFMONTH', $query, $where);
    }
    
    /**
     * Compile a "where month" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereMonth(Builder $query, $where)
    {
    	return $this->dateBasedWhere('month', $query, $where);
    }
    
    /**
     * Compile a "where year" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereYear(Builder $query, $where)
    {
    	return $this->dateBasedWhere('year', $query, $where);
    }
    
    /**
     * Compile the SQL statement to execute a savepoint rollback.
     *
     * @param  string  $name
     * @return string
     */
    public function compileSavepointRollBack($name)
    {
    	return 'ROLLBACK TO '.$name;
    }
    
    
    /**
     * Compile a "JSON length" statement into SQL.
     *
     * @param  string  $column
     * @param  string  $operator
     * @param  string  $value
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function compileJsonLength($column, $operator, $value)
    {
    	throw new RuntimeException('DBMaker does not support JSON length operations.');
    }
    
    
    /**
     * Compile a "JSON contains" statement into SQL.
     *
     * @param  string  $column
     * @param  string  $value
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function compileJsonContains($column, $value)
    {
    	throw new RuntimeException('DBMaker does not support JSON contains operations.');
    }
    
    
    /**
     * Compile a truncate table statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return array
     */
    public function compileTruncate(Builder $query)
    {
    	return ['delete from '.$this->wrapTable($query->from) => []];
    }
    
}
