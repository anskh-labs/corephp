<?php

declare(strict_types=1);

namespace Corephp\Db;

use Exception;

/**
 * Migration
 * -----------
 * Class to handle migration file
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Db
 */
abstract class Migration
{
    protected string $table;
    protected string $create_at = 'create_at';
    protected string $update_at = 'update_at';
    
    /**
     * up
     *
     * @return bool
     */
    public abstract function up(): bool;
    
    /**
     * seed
     *
     * @return bool
     */
    public abstract function seed(): bool;
    
    /**
     * down
     *
     * @return bool
     */
    public function down(): bool
    {
        try {
            db()->dropIfExist($this->table);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }    
}
