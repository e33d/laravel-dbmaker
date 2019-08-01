<?php
/**
 * Created by syscom.
 * User: syscom
 * Date: 17/06/2019
 * Time: 15:50
 */


namespace DBMaker\ODBC\Query\Processors;
 
use Illuminate\Database\Query\Processors\Processor;  
use Illuminate\Database\Query\Builder;

class DBMakerProcessor extends Processor
{
    /**
     * Process an "insert get ID" query.
     *
     * @param Builder $query
     * @param  string $sql
     * @param  array $values
     * @param  string $sequence
     * @return int
     */
	/* */
    public function processInsertGetId(Builder $query, $sql, $values, $sequence = null)
    {
        $query->getConnection()->insert($sql, $values);
        $id =  $query->getConnection()->table('sysconinfo')->max('LAST_SERIAL');
        return is_numeric($id) ? (int)$id : $id;
    }

    /**
     * @param Builder $query
     * @param null $sequence
     * @return mixed
     */
    public function getLastInsertId(Builder $query, $sequence = null){
        return $query->getConnection()->getPdo()->lastInsertId();
    }

}
