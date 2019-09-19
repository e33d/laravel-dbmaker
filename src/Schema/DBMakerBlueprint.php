<?php
/**
 * Created by syscom.
 * User: syscom
 * Date: 17/06/2019
 * Time: 15:50
 */

namespace DBMaker\ODBC\Schema;

use Closure;
use Illuminate\Database\Schema\Blueprint as Blueprint;
use Illuminate\Database\Schema\Grammars\Grammar as Grammar;


class DBMakerBlueprint extends Blueprint
{
	/**
	 * Create a new auto-incrementing integer (4-byte) column on the table.
	 *
	 * @param  string  $column
	 * @return \Illuminate\Database\Schema\ColumnDefinition
	 */
	public function increments($column)
	{
		return $this->serial($column, true);
	}
	
	/**
	 * Create a new auto-incrementing big integer (8-byte) column on the table.
	 *
	 * @param  string  $column
	 * @return \Illuminate\Database\Schema\ColumnDefinition
	 */
	public function bigIncrements($column)
	{
		return $this->bigserial($column, true);
	}
	
	 


	/**
	 * Create a new serial (4-byte) column on the table.
	 *
	 * @param  string  $column
	 * @return \Illuminate\Database\Schema\ColumnDefinition
	 */
	public function serial($column, $autoIncrement = false)
	{
		return $this->addColumn('serial', $column, compact('autoIncrement'));
	}
	
	
 


	/**
	 * Create a new bigserial (8-byte) column on the table.
	 *
	 * @param  string  $column
	 * @return \Illuminate\Database\Schema\ColumnDefinition
	 */
	public function bigserial($column, $autoIncrement = false)
	{
		return $this->addColumn('bigserial', $column, compact('autoIncrement'));
	}
	
	
	
	
	
}
