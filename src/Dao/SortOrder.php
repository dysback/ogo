<?php

declare(strict_types=1);

namespace Dysback\Ogo\Dao;

/**
 * Sort direction for DAO row filtering results.
 */
enum SortOrder: string
{
    /** Ascending order (default) */
    case ASC = 'ASC';

    /** Descending order */
    case DESC = 'DESC';
}
