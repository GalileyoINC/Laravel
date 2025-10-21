<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * Helper class for generating table filter configurations
 */
class TableFilterHelper
{
    /**
     * Generate a text filter column
     */
    public static function textColumn(string $label, ?string $placeholder = null, ?string $class = null): array
    {
        return [
            'label' => $label,
            'class' => $class,
            'filter' => [
                'type' => 'text',
                'placeholder' => $placeholder ?? $label,
            ],
        ];
    }

    /**
     * Generate a select filter column
     */
    public static function selectColumn(string $label, array $options, ?string $class = null): array
    {
        return [
            'label' => $label,
            'class' => $class,
            'filter' => [
                'type' => 'select',
                'options' => $options,
            ],
        ];
    }

    /**
     * Generate a date filter column
     */
    public static function dateColumn(string $label, ?string $class = null): array
    {
        return [
            'label' => $label,
            'class' => $class,
            'filter' => [
                'type' => 'date',
            ],
        ];
    }

    /**
     * Generate a column without filter
     */
    public static function noFilterColumn(string $label, ?string $class = null): array
    {
        return [
            'label' => $label,
            'class' => $class,
            'filter' => false,
        ];
    }

    /**
     * Generate a clear button column
     */
    public static function clearButtonColumn(string $label = 'Actions', ?string $class = 'action-column-2'): array
    {
        return [
            'label' => $label,
            'class' => $class,
            'filter' => [
                'type' => 'button',
            ],
        ];
    }

    /**
     * Generate boolean select options (Yes/No)
     */
    public static function booleanOptions(): array
    {
        return [
            'yes' => 'Yes',
            'no' => 'No',
        ];
    }

    /**
     * Generate status select options
     */
    public static function statusOptions(): array
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];
    }

    /**
     * Generate active/cancelled options
     */
    public static function activeCancelledOptions(): array
    {
        return [
            'active' => 'Active',
            'cancelled' => 'Cancelled',
        ];
    }

    /**
     * Quick config for common user table
     */
    public static function userTableColumns(): array
    {
        return [
            self::textColumn('ID', 'ID', 'grid__id'),
            self::textColumn('First Name'),
            self::textColumn('Last Name'),
            self::textColumn('Email'),
            self::noFilterColumn('Phones'),
            self::selectColumn('Status', self::activeCancelledOptions()),
            self::selectColumn('Influencer', self::booleanOptions()),
            self::selectColumn('Test', self::booleanOptions()),
            self::selectColumn('Refer', ['1' => 'Direct', '2' => 'Referral']),
            self::textColumn('Created At'),
            self::textColumn('Updated At'),
            self::clearButtonColumn(),
        ];
    }
}
