<?php

namespace ligm\Barm;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model.
 */
class BonusActiveRecordModel extends ActiveRecordModel
{
    /**
     * Find and return all matching the search criteria.
     *
     *
     * The `$value` can be a single value or an array of values.
     *
     * @param string  $orderBy for building the order by part of the query.
     * @param string  $limit for building the LIMIT part of the query.
     *
     * @return array of object of this class
     */
    public function findAllOrderBy($orderBy, $limit = 10000)
    {
        $this->checkDb();
        return $this->db->connect()
            ->select()
            ->from($this->tableName)
            ->orderBy($orderBy)
            ->limit($limit)
            ->execute()
            ->fetchAllClass(get_class($this));
    }

    /**
     * Find and return all matching the search criteria.
     *
     *
     * The `$value` can be a single value or an array of values.
     *
     * @param string  $orderBy for building the order by part of the query.
     * @param string  $limit for building the LIMIT part of the query.
     *
     * @return array of object of this class
     */
    public function findAllWhereOrderBy($orderBy, $where, $value, $limit = 1000)
    {
        $this->checkDb();
        $params = is_array($value) ? $value : [$value];
        return $this->db->connect()
            ->select()
            ->from($this->tableName)
            ->orderBy($orderBy)
            ->where($where)
            ->limit($limit)
            ->execute($params)
            ->fetchAllClass(get_class($this));
    }

    /**
     * Find and return all matching the search criteria.
     *
     *
     *
     * @param string  $orderBy for building the order by part of the query.
     * @param string  $groupBy for building the group by part of the query.
     * @param string  $limit for building the LIMIT part of the query.
     * @param string $joinTable Which table to join.
     * @param string $joinOn Where to join the table.
     *
     * @return array of object of this class
     */
    public function findAllOrderByGroupBy($orderBy, $groupBy, $limit = 1000, $select = "*")
    {
        $this->checkDb();
        return $this->db->connect()
            ->select($select)
            ->from($this->tableName)
            ->groupBy($groupBy)
            ->orderBy($orderBy)
            ->limit($limit)
            ->execute()
            ->fetchAllClass(get_class($this));
    }


    /**
     * Select all where
     *
     *
     *
     * @param string  $orderBy for building the order by part of the query.
     * @param string  $limit for building the LIMIT part of the query.
     *
     * @return array of object of this class
     */
    public function selectWhere($select, $where, $value)
    {
        $this->checkDb();
        $params = is_array($value) ? $value : [$value];
        return $this->db->connect()
            ->select($select)
            ->from($this->tableName)
            ->where($where)
            ->execute($params)
            ->fetchAllClass(get_class($this));
    }

    /**
     * Find and return all matching the search criteria.
     *
     *
     *
     * @param string  $orderBy for building the order by part of the query.
     * @param string  $groupBy for building the group by part of the query.
     * @param string  $limit for building the LIMIT part of the query.
     * @param string $joinTable Which table to join.
     * @param string $joinOn Where to join the table.
     *
     * @return array of object of this class
     */
    public function findAllJoinOrderByGroupBy($orderBy, $groupBy, $joinTable, $joinOn, $limit = 10000, $select = "*")
    {
        $this->checkDb();
        return $this->db->connect()
            ->select($select)
            ->from($this->tableName)
            ->groupBy($groupBy)
            ->orderBy($orderBy)
            ->join($joinTable, $joinOn)
            ->limit($limit)
            ->execute()
            ->fetchAllClass(get_class($this));
    }
}
