<?php

declare(strict_types=1);

namespace Dysback\Ogo\Dao;

use Dysback\Ogo\Database\IDatabase;

/**
 * Interface for Data Access Objects.
 *
 * Defines the standard CRUD operations that every DAO must implement.
 * The primary key is always an autoincrement integer column named `id`
 * (overridable in BaseDao).
 */
interface IDao
{
    /**
     * Finds a single record by its primary key and populates the object's properties.
     *
     * @param int $id The primary key value.
     *
     * @return bool True if the record was found and properties were populated, false otherwise.
     */
    public function findById(int $id): bool;

    /**
     * Returns all records from the table.
     *
     * @return array An array of associative arrays representing all rows.
     */
    public function findAll(): array;

    /**
     * Inserts the object's properties as a new row and returns the generated primary key.
     *
     * @return int The autoincrement ID of the newly inserted row.
     */
    public function insert(): int;

    /**
     * Updates or inserts the current object. Calls update() if the primary key
     * property is set, otherwise calls insert().
     *
     * @return int|bool The new ID on insert, true/false on update.
     */
    public function save(): int|bool;

    /**
     * Updates the database row identified by the object's primary key property
     * with the object's current property values.
     *
     * @return bool True if the update succeeded, false otherwise.
     */
    public function update(): bool;

    /**
     * Deletes the row identified by the object's primary key property.
     *
     * @return bool True if the deletion succeeded, false otherwise.
     */
    public function delete(): bool;

    /**
     * Populates the object's properties from an associative array by matching key names.
     *
     * @param array $data Associative array of column => value pairs.
     *
     * @return void
     */
    public function getFromArray(array $data): void;

    /**
     * Returns the name of the database table this DAO operates on.
     *
     * @return string The table name.
     */
    public function getTable(): string;

    /**
     * Returns the primary key column name for this DAO's table.
     *
     * @return string The primary key column name.
     */
    public function getPrimaryKey(): string;

    /**
     * Returns the database connection used by this DAO.
     *
     * @return IDatabase The database connection.
     */
    public function getDatabase(): IDatabase;
}
