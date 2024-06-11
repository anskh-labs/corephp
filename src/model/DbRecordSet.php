<?php

declare(strict_types=1);

namespace Corephp\Model;

/**
 * DbRecordSet
 * -----------
 * Storage to store query result with pagination
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Model
 */
class DbRecordSet
{
    private array $rows;
    private DbPaginationInterface $pagination;    
    
    /**
     * __construct
     *
     * @param  mixed $rows
     * @param  mixed $pagination
     * @return void
     */
    public function __construct(array $rows, DbPaginationInterface $pagination)
    {
        $this->rows = $rows;
        $this->pagination = $pagination;
    }    
    /**
     * rows
     *
     * @return array
     */
    public function getRows(): array
    {
        return $this->rows;
    }    
    /**
     * pagination
     *
     * @return DbPaginationInterface
     */
    public function getPagination(): DbPaginationInterface
    {
        return $this->pagination;
    }
}
