<?php

declare(strict_types=1);

namespace Dysback\Ogo\Dao;

/**
 * Value object representing a single filter condition for DaoFilter.
 *
 * - For NULL / NNULL operators, omit value (or pass null).
 * - For IN, pass an array of values.
 * - For BETWEEN, pass a two-element array [min, max].
 * - For all other operators, pass a scalar value.
 */
class Filter
{
    /**
     * @param string         $column   The column name to filter on.
     * @param FilterOperator $operator The comparison operator to apply.
     * @param mixed          $value    The value(s) to compare against.
     */
    public function __construct(
        public readonly string $column,
        public readonly FilterOperator $operator,
        public readonly mixed $value = null,
    ) {}
}
