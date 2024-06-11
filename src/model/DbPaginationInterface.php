<?php

declare(strict_types=1);

namespace Corephp\Model;

/**
 * DbPaginationInterface
 * -----------
 * DbPaginationInterface
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Model
 */
interface DbPaginationInterface
{    
    /**
     * pageCount
     *
     * @return int
     */
    public function pageCount(): int;    
    /**
     * recordCount
     *
     * @return int
     */
    public function recordCount(): int;    
    /**
     * perPage
     *
     * @return int
     */
    public function perPage(): int;    
    /**
     * currentPage
     *
     * @return int
     */
    public function currentPage(): int;    
    /**
     * offset
     *
     * @return int
     */
    public function offset(): int;    
    /**
     * html
     *
     * @return string
     */
    public function html(): string;
}
