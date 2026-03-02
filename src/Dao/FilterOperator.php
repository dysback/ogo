<?php

declare(strict_types=1);

namespace Dysback\Ogo\Dao;

use Exception;

/**
 * Comparison operators available for DAO row filtering.
 *
 * Used in Filter value objects to express a condition on a single column.
 * NULL and NNULL operators do not require a value.
 * IN and BETWEEN operators require the value to be an array.
 */
enum FilterOperator: string
{
    /** Equal ( = ) */
    case EQ = '=';
    case EQUAL = ' =';

    /** Not equal ( <> ) */
    case NE = '<>';
    case NOT_EQUAL = ' <>';

    /** Less than ( < ) */
    case LT = '<';
    case LESS_THAN = ' <';

    /** Greater than ( > ) */
    case GT = '>';
    case GREATER_THAN = ' >'; 

    /** Greater than or equal ( >= ) */
    case GE = '>=';
    case GREATER_OR_EQUAL_THAN = ' >=';

    /** Less than or equal ( <= ) */
    case LE = '<=';
    case LESS_OR_EQUAL_THAN = ' <=';

    /** Is NULL — no value required */
    case NULL = 'IS NULL';
    case IS_NULL = ' IS NULL';

    /** Is not NULL — no value required */
    case NNULL = 'IS NOT NULL';
    case NOT_NULL = ' IS NOT NULL';
    case IS_NOT_NULL = ' IS NOT NULL ';

    /** SQL LIKE pattern match */
    case LIKE = 'LIKE';

    /** SQL IN list — value must be an array */
    case IN = 'IN';

    /** SQL BETWEEN range — value must be a two-element array [min, max] */
    case BETWEEN = 'BETWEEN';

    public static function getByName(string $OperatorName) : FilterOperator {
        foreach(FilterOperator::cases() as $operator) {
            if($operator->name == $OperatorName) {
                return $operator;
            }
        }
        throw new Exception("Operator $OperatorName not found");
    }
}
