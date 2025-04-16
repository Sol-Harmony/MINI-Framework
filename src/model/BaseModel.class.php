<?php

/**
 * This project is licensed under the GNU LGPL v2.1.
 * You may use, modify, and distribute it under the terms of this license.
 * Modifications must remain open-source under the same license.
 * 
 * Copyright (C) 2025 Hamzah Mansor 
 **/
class BaseModel extends dbConnect
{
    protected $table;
    protected $primaryKey = 'id';

    public function find($id)
    {
        $connector = $this->connect();
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $connector->prepare($sql);
        $stmt->execute([$id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new RowModel($this->table, $data);
        }

        return null;
    }

    public function all()
    {
        $connector = $this->connect();
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $connector->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($data) => new RowModel($this->table, $data), $rows);
    }

    public function where($conditions)
    {
        $connector = $this->connect();

        $sqlParts = [];
        $values = [];

        foreach ($conditions as $key => $val) {
            $sqlParts[] = "$key = ?";
            $values[] = $val;
        }

        $sql = "SELECT * FROM {$this->table} WHERE " . implode(" AND ", $sqlParts);
        $stmt = $connector->prepare($sql);
        $stmt->execute($values);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($data) => new RowModel($this->table, $data), $rows);
    }

    public function create($data = [])
    {
        return new RowModel($this->table, $data);
    }
}
