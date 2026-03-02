<?php

declare(strict_types=1);

namespace Dysback\Ogo\Dao;

use FilterIterator;

/**
 * Filters rows from a DAO's table using a list of Filter conditions.
 *
 * Column names are validated against the live table schema before any SQL
 * is built. All filters are combined with AND. Results support pagination
 * (offset / limit) and sort direction on the primary key.
 *
 * Usage example:
 *   $filter = new DaoFilter($userDao);
 *   $rows   = $filter->filter(
 *       filters: [
 *           new Filter('status', FilterOperator::EQ,      'active'),
 *           new Filter('age',    FilterOperator::BETWEEN, [18, 30]),
 *       ],
 *       limit: 20,
 *       order: SortOrder::DESC,
 *   );
 */
class DaoFilter
{
    /** @var IDao The DAO whose table is being queried. */
    private IDao $dao;

    /** @var array<string>|null Lazily loaded list of valid column names. */
    private ?array $columns = null;

    /**
     * @param IDao $dao The DAO to filter rows from.
     */
    public function __construct(IDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Returns rows matching all supplied filters.
     *
     * All filters are AND-combined. Empty $filters returns all rows (with
     * pagination / ordering still applied).
     *
     * @param Filter[]  $filters List of Filter value objects.
     * @param int       $offset  Number of rows to skip (default 0).
     * @param int|null  $limit   Maximum rows to return; null means unlimited.
     * @param SortOrder $order   Sort direction on the primary key (default ASC).
     *
     * @return array Array of associative-array rows matching the filters.
     *
     * @throws \InvalidArgumentException If any filter references an unknown column.
     */
    public function filter(
        array $filters,
        int $offset = 0,
        ?int $limit = null,
        SortOrder $order = SortOrder::ASC,
    ): array {
        $this->validateFilters($filters);

        $table      = $this->dao->getTable();
        $primaryKey = $this->dao->getPrimaryKey();

        [$whereClause, $params] = $this->buildWhere($filters);

        $sql = "SELECT * FROM `{$table}`";

        if ($whereClause !== '') {
            $sql .= " WHERE {$whereClause}";
        }

        $sql .= " ORDER BY `{$primaryKey}` {$order->value}";

        if ($limit !== null) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        } elseif ($offset > 0) {
            // MySQL requires a LIMIT when using OFFSET without a bound
            $sql .= ' LIMIT 18446744073709551615 OFFSET ' . $offset;
        }
        error_log($sql . " - " .  print_r($params, true));
        return $this->dao->getDatabase()->fetchAll($sql, $params);
    }

    /**
     * Loads valid column names from the database schema and caches them.
     *
     * @return array<string> List of column names for the DAO's table.
     */
    private function loadColumns(): array
    {
        if ($this->columns === null) {
            $rows = $this->dao->getDatabase()->fetchAll(
                'SHOW COLUMNS FROM `' . $this->dao->getTable() . '`'
            );
            $this->columns = array_column($rows, 'Field');
        }

        return $this->columns;
    }

    /**
     * Validates that every filter references a column that exists in the table.
     *
     * @param Filter[] $filters
     *
     * @throws \InvalidArgumentException On the first unknown column name.
     */
    private function validateFilters(array $filters): void
    {
        $valid = $this->loadColumns();

        foreach ($filters as $filter) {
            if (!in_array($filter->column, $valid, strict: true)) {
                throw new \InvalidArgumentException(
                    "Unknown column \"{$filter->column}\" in table \"{$this->dao->getTable()}\"."
                );
            }
        }
    }

    /**
     * Builds the WHERE clause string and collects bound parameter values.
     *
     * @param Filter[] $filters
     *
     * @return array{0: string, 1: array} Tuple of [whereClause, params].
     */
    private function buildWhere(array $filters): array
    {
        $parts  = [];
        $params = [];

        foreach ($filters as $filter) {
            $col = "`{$filter->column}`";
            $op  = $filter->operator;

            switch ($op) {
                case FilterOperator::NULL:
                case FilterOperator::NNULL:
                case FilterOperator::IS_NULL:
                case FilterOperator::IS_NOT_NULL:
                case FilterOperator::NOT_NULL:
                    $parts[] = "{$col} {$op->value}";
                    break;

                case FilterOperator::IN:
                    $placeholders = implode(', ', array_fill(0, count($filter->value), '?'));
                    $parts[]      = "{$col} IN ({$placeholders})";
                    array_push($params, ...$filter->value);
                    break;

                case FilterOperator::BETWEEN:
                    $parts[]  = "{$col} BETWEEN ? AND ?";
                    $params[] = $filter->value[0];
                    $params[] = $filter->value[1];
                    break;

                default:
                    $parts[]  = "{$col} {$op->value} ?";
                    $params[] = $filter->value;
                    break;
            }
        }

        return [implode(' AND ', $parts), $params];
    }
}
