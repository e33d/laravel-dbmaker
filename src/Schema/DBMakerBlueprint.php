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
use DBMaker\ODBC\Schema\ColumnDefinition;

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
		return $this->serial($column)->nullable();;
	}
	
	/**
	 * Create a new integer (4-byte) column on the table.
	 *
	 * @param  string  $column
	 * @return \Illuminate\Database\Schema\ColumnDefinition
	 */
	public function serial($column)
	{
		return $this->addColumn('serial', $column);
	}
}
