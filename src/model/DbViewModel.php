<?php

declare(strict_types=1);

namespace Corephp\Model;

use Corephp\Db\Database;
use Corephp\Html\Pagination;
use Exception;
use PDO;

/**
 * DbViewModel
 * -----------
 * DbViewModel
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Model
 */
abstract class DbViewModel extends Model
{
    protected Database $db;
    protected string $table;

    /**
     * __construct
     *
     * @param  mixed $db
     * @return void
     */
    public function __construct(?Database $db = null)
    {
        $this->db = $db ?? db();
    }

    /**
     * getTable
     *
     * @return string
     */
    public function getTable(): string
    {
        if (!$this->table) {
            throw new Exception('This method must be implemented.');
        }
        return $this->table;
    }

    /**
     * getRecordCount
     *
     * @param  mixed $where
     * @return int
     */
    public function getRecordCount(array|string|null $where = null): int
    {
        return $this->db->getRecordCount($this->table, $where);
    }

    /**
     * isExists
     *
     * @param  mixed $where
     * @return bool
     */
    public function isExists(array|string|null $where = null): bool
    {
        return $this->db->recordExists($this->table, $where);
    }

    /**
     * table
     *
     * @return string
     */
    public static function table(): string
    {
        throw new Exception('This method must be implemented.');
    }
    
    /**
     * column
     *
     * @return string
     */
    public static function column(): string
    {
        throw new Exception('This method must be implemented.');
    }
    /**
     * Get db
     *
     * @param  mixed $db
     * @return Database
     */
    public static function db(?Database $db = null): Database
    {
        return $db ?? db();
    }

    /**
     * all
     *
     * @param  mixed $column
     * @param  mixed $limit
     * @param  mixed $offset
     * @param  mixed $orderby
     * @param  mixed $db
     * @return array
     */
    public static function all(string $column = '*', int $limit = 0, int $offset = -1, string|null $orderby = null, ?Database $db = null): array
    {
        if($column === '*'){
            $column = static::column();
        }
        return static::db($db)->select(static::table(), $column, null, $limit, $offset, $orderby);
    }

    /**
     * allColumn
     *
     * @param  mixed $column
     * @param  mixed $limit
     * @param  mixed $offset
     * @param  mixed $orderby
     * @param  mixed $db
     * @return array
     */
    public static function allColumn(string $column, int $limit = 0, int $offset = -1, string|null $orderby = null, ?Database $db = null): array
    {
        return static::db($db)->select(static::table(), $column, null, $limit, $offset, $orderby, PDO::FETCH_COLUMN);
    }

    /**
     * row
     *
     * @param  mixed $column
     * @param  mixed $where
     * @param  mixed $db
     * @return array
     */
    public static function row(string $column = '*', array|string|null $where = null, ?Database $db = null): array
    {
        if($column === '*'){
            $column = static::column();
        }
        return static::db($db)->getRow(static::table(), $column, $where);
    }

    /**
     * recordCount
     *
     * @param  mixed $where
     * @param  mixed $db
     * @return int
     */
    public static function recordCount(array|string|null $where = null, ?Database $db = null): int
    {
        return static::db($db)->getRecordCount(static::table(), $where);
    }

    /**
     * find
     *
     * @param  mixed $where
     * @param  mixed $column
     * @param  mixed $limit
     * @param  mixed $orderby
     * @param  mixed $db
     * @return array
     */
    public static function find(array|string|null $where = null, string $column = '*', int $limit = 0, int $offset = -1, string|null $orderby = null, ?Database $db = null): array
    {
        if($column === '*'){
            $column = static::column();
        }
        return static::db($db)->select(static::table(), $column, $where, $limit, $offset, $orderby);
    }

    /**
     * paginate
     *
     * @param  mixed $where
     * @param  mixed $column
     * @param  mixed $perpage
     * @param  mixed $orderby
     * @param  mixed $db
     * @return DbRecordSet
     */
    public static function paginate(array|string|null $where = null, string $column = '*', null|int $perpage = null, string|null $orderby = null, ?Database $db=null): DbRecordSet
    {
        $pager = new Pagination(static::recordCount($where), $perpage);
        if($column === '*'){
            $column = static::column();
        }
        $rows = static::db($db)->select(static::table(), $column, $where, $pager->perPage(), $pager->offset(), $orderby);
        return new DbRecordSet($rows, $pager);
    }

    /**
     * findColumn
     *
     * @param  mixed $where
     * @param  mixed $column
     * @param  mixed $limit
     * @param  mixed $offset
     * @param  mixed $orderby
     * @param  mixed $db
     * @return array
     */
    public static function findColumn(array|string|null $where = null, string $column = '*', int $limit = 0, int $offset = -1, string|null $orderby = null, ?Database $db = null): array
    {
        if($column === '*'){
            $column = static::column();
        }
        return static::db($db)->select(static::table(), $column, $where, $limit, $offset, $orderby, PDO::FETCH_COLUMN);
    }

    /**
     * exists
     *
     * @param  mixed $where
     * @param  mixed $db
     * @return bool
     */
    public static function exists(array|string|null $where = null, ?Database $db = null): bool
    {
        return static::db($db)->recordExists(static::table(), $where);
    }
}
