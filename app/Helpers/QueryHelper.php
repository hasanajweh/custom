<?php

namespace App\Helpers;

class QueryHelper
{
    /**
     * Sanitize sort column to prevent SQL injection
     *
     * @param string $column
     * @param array $allowed
     * @return string
     */
    public static function sanitizeSortColumn(string $column, array $allowed): string
    {
        return in_array($column, $allowed, true) ? $column : $allowed[0];
    }

    /**
     * Sanitize sort direction
     *
     * @param string $direction
     * @return string
     */
    public static function sanitizeSortDirection(string $direction): string
    {
        $direction = strtolower($direction);
        return in_array($direction, ['asc', 'desc'], true) ? $direction : 'desc';
    }

    /**
     * Build safe where clause for multiple values
     *
     * @param array $values
     * @param array $allowed
     * @return array
     */
    public static function sanitizeWhereIn(array $values, array $allowed): array
    {
        return array_values(array_intersect($values, $allowed));
    }

    /**
     * Validate and sanitize pagination parameters
     *
     * @param int $perPage
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function sanitizePerPage(int $perPage, int $min = 10, int $max = 100): int
    {
        return max($min, min($max, $perPage));
    }
}
