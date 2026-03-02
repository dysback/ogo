<?php

declare(strict_types=1);

namespace Dysback\Ogo\Dao;

use Dysback\Ogo\App;
use Dysback\Ogo\Database\IDatabase;

/**
 * Abstract base class for Data Access Objects.
 *
 * Subclasses must declare the $table property, add public properties for each
 * table column, and may override $primaryKey.
 *
 * Usage example:
 *   class UserDao extends BaseDao {
 *       protected string $table = 'users';
 *       public int $id;
 *       public string $username;
 *   }
 *
 *   $user = new UserDao($app);
 *   $user->findById(1);          // populates $user->id, $user->username, …
 *   $user->username = 'new';
 *   $user->update();             // persists changes
 *
 *   $user2 = new UserDao($app);
 *   $user2->username = 'alice';
 *   $id = $user2->insert();      // inserts and sets $user2->id
 */
abstract class BaseDao implements IDao
{
    /** @var IDatabase The database connection provided by the application. */
    protected IDatabase $db;

    /** @var string The database table this DAO operates on. */
    abstract protected string $table { get; }

    /** @var string The primary key column name (autoincrement integer). */
    protected string $primaryKey = 'id';

    /**
     * Initialises the DAO with the application's database connection.
     *
     * @param App $app The application instance.
     */
    public function __construct(App $app)
    {
        $this->db = $app->database;
    }

    /**
     * Returns the values of all initialized public properties, optionally
     * excluding the primary key column.
     *
     * @param bool $excludePrimaryKey Whether to omit the primary key property.
     *
     * @return array Associative array of column => value pairs.
     */
    private function getColumnData(bool $excludePrimaryKey = false): array
    {
        $data = [];
        foreach ((new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            if ($excludePrimaryKey && $prop->getName() === $this->primaryKey) {
                continue;
            }
            if ($prop->isInitialized($this)) {
                $data[$prop->getName()] = $prop->getValue($this);
            }
        }
        return $data;
    }

    /**
     * Finds a single record by its primary key and populates the object's properties.
     *
     * @param int $id The primary key value.
     *
     * @return bool True if the record was found and properties were populated, false otherwise.
     */
    public function findById(int $id): bool
    {
        $row = $this->db->fetch(
            "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ? LIMIT 1",
            [$id]
        );
        if ($row === false) {
            return false;
        }
        foreach ($row as $column => $value) {
            if (property_exists($this, $column)) {
                $this->{$column} = $value;
            }
        }
        return true;
    }

    /**
     * Populates the object's properties from an associative array by matching key names.
     *
     * @param array $data Associative array of column => value pairs.
     *
     * @return void
     */
    public function getFromArray(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Returns all records from the table.
     *
     * @return array An array of associative arrays representing all rows.
     */
    public function findAll(): array
    {
        return $this->db->fetchAll("SELECT * FROM `{$this->table}`");
    }

    /**
     * Inserts the object's properties as a new row and returns the generated primary key.
     * Sets the primary key property to the new ID after a successful insert.
     *
     * @return int The autoincrement ID of the newly inserted row.
     */
    public function insert(): int
    {
        $data = $this->getColumnData(excludePrimaryKey: true);
        $columns = implode(', ', array_map(fn($col) => "`{$col}`", array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $this->db->execute(
            "INSERT INTO `{$this->table}` ({$columns}) VALUES ({$placeholders})",
            array_values($data)
        );

        $id = (int) $this->db->lastInsertId();
        $this->{$this->primaryKey} = $id;
        return $id;
    }

    /**
     * Updates or inserts the current object. Calls update() if the primary key
     * property is set, otherwise calls insert().
     *
     * @return int|bool The new ID on insert, true/false on update.
     */
    public function save(): int|bool
    {
        $pkValue = $this->{$this->primaryKey} ?? null;
        return empty($pkValue) ? $this->insert() : $this->update();
    }

    /**
     * Updates the database row identified by the object's primary key property
     * with the object's current property values.
     *
     * @return bool True if the update succeeded, false otherwise.
     */
    public function update(): bool
    {
        $data = $this->getColumnData(excludePrimaryKey: true);
        $setParts = implode(', ', array_map(fn($col) => "`{$col}` = ?", array_keys($data)));

        return $this->db->execute(
            "UPDATE `{$this->table}` SET {$setParts} WHERE `{$this->primaryKey}` = ?",
            [...array_values($data), $this->{$this->primaryKey}]
        );
    }

    /**
     * Deletes the row identified by the object's primary key property.
     *
     * @return bool True if the deletion succeeded, false otherwise.
     */
    public function delete(): bool
    {
        return $this->db->execute(
            "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?",
            [$this->{$this->primaryKey}]
        );
    }

    /**
     * Returns the name of the database table this DAO operates on.
     *
     * @return string The table name.
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Returns the primary key column name for this DAO's table.
     *
     * @return string The primary key column name.
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * Returns the database connection used by this DAO.
     *
     * @return IDatabase The database connection.
     */
    public function getDatabase(): IDatabase
    {
        return $this->db;
    }
}
