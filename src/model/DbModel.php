<?php

declare(strict_types=1);

namespace Corephp\Model;

use Corephp\Db\Database;
use Corephp\Html\Pagination;
use PDO;

/**
 * DbModel
 * -----------
 * DbModel
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Model
 */
abstract class DbModel extends Model
{
    const ID = 'id';

    protected Database $db;
    protected string $table;
    protected bool $autoIncrement = true;
    protected string $primaryKey = 'id';
    protected array $fields = [];
    protected bool $editMode = false;

    /**
     * __construct
     *
     * @param  mixed $db
     * @return void
     */
    public function __construct(?Database $db = null)
    {
        $this->db = $db ?? db();
        if (!isset($this->table)) {
            $arr = explode('\\', static::class);
            $this->table = $this->db->getTable(strtolower(end($arr)));
        }
        if (empty($this->fields)) {
            $this->generateFields();
        }
    }

    /**
     * getTable
     *
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * getPrimaryKey
     *
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * getFields
     *
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }
    /**
     * addProperty
     *
     * @param  mixed $property
     * @param  mixed $type
     * @param  mixed $defaultValue
     * @return void
     */
    public function addProperty(string $property, string $type = self::TYPE_STRING, mixed $defaultValue = null): void
    {
        $this->fields[] = $property;
        $this->types[$property] = $type;
        $this->storage[$property] = $defaultValue;
    }
    /**
     * load
     *
     * @param  mixed $pk
     * @return bool
     */
    public function load(string|int $pk): bool
    {
        $data = $this->db->select($this->table, '*', [$this->primaryKey . "=" => $pk], 1);

        if ($data) {
            foreach ($data as $row) {
                $this->fill($row);
            }
            return true;
        }

        return false;
    }

    /**
     * save
     *
     * @param  mixed $isUpdate
     * @return bool
     */
    public function save(bool $isUpdate = false): bool
    {
        $data = [];
        foreach ($this->fields as $field) {
            $data[$field] = $this->{$field} ?? null;
        }
        if ($isUpdate) {
            $pk = $this->primaryKey;
            unset($data[$pk]);
            return $this->db->update($data, $this->table, [$pk . '=' => $this->{$pk}]) >= 0 ? true : false;
        } else {
            return $this->db->insert($data, $this->table) > 0 ? true : false;
        }
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
     * generateFields
     *
     * @return void
     */
    protected function generateFields(): void
    {
        $stmt = $this->db->query("SELECT * FROM " . $this->db->getTable($this->table) . " LIMIT 0;");
        $columnCount = $stmt->columnCount();
        for ($i = 0; $i < $columnCount; $i++) {
            $col = $stmt->getColumnMeta($i);
            $this->fields[] = $col['name'];
            $type =  in_array($col['native_type'], [self::TYPE_BOOL, self::TYPE_FLOAT, self::TYPE_INT, self::TYPE_STRING]) ? $col['native_type'] : self::TYPE_STRING;
            $this->addProperty($col['name'], $type);
        }

        if ($this->autoIncrement) {
            unset($this->fields[$this->primaryKey]);
            unset($this->types[$this->primaryKey]);
            unset($this->storage[$this->primaryKey]);
        }
    }

    /**
     * table
     *
     * @return string
     */
    public static function table(): string
    {
        $arr = explode('\\', static::class);
        return strtolower(end($arr));
    }

    /**
     * primaryKey
     *
     * @return string
     */
    public static function primaryKey(): string
    {
        return static::ID;
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
     * create
     *
     * @param  mixed $data
     * @param  mixed $db
     * @return int
     */
    public static function create(array $data, ?Database $db = null): int
    {
        return static::db($db)->insert($data, static::table());
    }

    /**
     * update
     *
     * @param  mixed $data
     * @param  mixed $where
     * @param  mixed $db
     * @return int
     */
    public static function update(array $data, array|string|null $where = null, ?Database $db = null): int
    {
        return static::db($db)->update($data, static::table(), $where);
    }

    /**
     * delete
     *
     * @param  mixed $where
     * @param  mixed $db
     * @return int
     */
    public static function delete(array|string|null $where = null, ?Database $db = null): int
    {
        return static::db($db)->delete(static::table(), $where);
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
    public static function paginate(array|string|null $where = null, string $column = '*', null|int $perpage = null, string|null $orderby = null, ?Database $db = null): DbRecordSet
    {
        $pager = new Pagination(static::recordCount($where), $perpage);
        $rows = static::db($db)->select(static::table(), $column, $where, $pager->perPage(), $pager->offset(), $orderby);
        return new DbRecordSet($rows, $pager);
    }

    /**
     * findColumn
     *
     * @param  mixed $column
     * @param  mixed $where
     * @param  mixed $limit
     * @param  mixed $offset
     * @param  mixed $orderby
     * @param  mixed $db
     * @return array
     */
    public static function findColumn(string $column, array|string|null $where = null, int $limit = 0, int $offset = -1, string|null $orderby = null, ?Database $db = null): array
    {
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
