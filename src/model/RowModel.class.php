<?php

/**
 * This project is licensed under the GNU LGPL v2.1.
 * You may use, modify, and distribute it under the terms of this license.
 * Modifications must remain open-source under the same license.
 * 
 * Copyright (C) 2025 Hamzah Mansor 
 **/
class RowModel extends dbConnect
{
    protected $table;
    protected $primaryKey = 'id';
    protected $attributes = [];

    public function __construct($table, $data = [])
    {
        $this->table = $table;
        $this->attributes = $data;
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function save()
    {
        $connector = $this->connect();

        if (isset($this->attributes[$this->primaryKey])) {
            // UPDATE
            $id = $this->attributes[$this->primaryKey];
            $setParts = [];

            foreach ($this->attributes as $key => $val) {
                if ($key !== $this->primaryKey) {
                    $setParts[] = "$key = ?";
                }
            }

            $sql = "UPDATE {$this->table} SET " . implode(', ', $setParts) . " WHERE {$this->primaryKey} = ?";
            $stmt = $connector->prepare($sql);

            $values = array_values(array_filter(
                $this->attributes,
                fn($key) => $key !== $this->primaryKey,
                ARRAY_FILTER_USE_KEY
            ));
            $values[] = $id;

            $stmt->execute($values);
        } else {
            // INSERT
            $columns = implode(', ', array_keys($this->attributes));
            $placeholders = implode(', ', array_fill(0, count($this->attributes), '?'));

            $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
            $stmt = $connector->prepare($sql);
            $stmt->execute(array_values($this->attributes));

            $this->attributes[$this->primaryKey] = $connector->lastInsertId();
        }

        // Return fresh row from DB
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $connector->prepare($sql);
        $stmt->execute([$this->attributes[$this->primaryKey]]);

        $this->attributes = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this;
    }

    public function delete()
    {
        if (!isset($this->attributes[$this->primaryKey])) {
            throw new Exception("Cannot delete row without primary key.");
        }

        $connector = $this->connect();
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $connector->prepare($sql);
        $stmt->execute([$this->attributes[$this->primaryKey]]);
    }

    public function toArray()
    {
        return $this->attributes;
    }
}
