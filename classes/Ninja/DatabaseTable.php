<?php

namespace Ninja;

class DatabaseTable
{

    public $pdo;
    public $table;
    public $primaryKey;

    public function __construct(\PDO $pdo, string $table, string $primaryKey)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    /** 
     * 
     * Private Function 
     * 
     * */

    private function query(string $sql, array $parameters = [])
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($parameters);

        return $query;
    }

    private function insert(array $fields)
    {
        $query = 'INSERT INTO `' . $this->table . '` (';

        foreach ($fields as $key => $value) {
            $query .= '`' . $key . '`,';
        }

        $query = rtrim($query, ',');

        $query .= ') VALUES (';

        foreach ($fields as $key => $value) {
            $query .= ':' . $key . ',';
        }

        $query = rtrim($query, ',');

        $query .= ')';

        $fields = $this->processDates($fields);

        $this->query($query, $fields);
    }

    private function update(array $fields)
    {
        $query = 'UPDATE `' . $this->table . '` SET';

        foreach ($fields as $key => $value) {
            $query .= '`' . $key . '` = :' . $key . ',';
        }
        $query = rtrim($query, ',');

        $query .= ' WHERE `' . $this->primaryKey . '` = :primaryKey';

        $fields = $this->processDates($fields);

        //Imposta la variabile :primaryKey
        $fields['primaryKey'] = $fields['id'];

        $this->query($query, $fields);
    }

    private function processDates(array $fields)
    {
        foreach ($fields as $key => $value) {
            if ($value instanceof \DateTime) {
                $fields[$key] = $value->format('Y-m-d');
            }
        }

        return $fields;
    }

    /** 
     * 
     * Public Function 
     * 
     * */

    public function findAll()
    {
        $result = $this->query('SELECT * FROM `' . $this->table . '`');

        return $result->fetchAll();
    }

    public function delete(string $id)
    {
        $parameters = [':id' => $id];

        $query = 'DELETE FROM `' . $this->table . '` WHERE `' . $this->primaryKey . '` = :id';

        $this->query($query, $parameters);
    }

    public function findById(string $value)
    {

        $query = 'SELECT * FROM `' . $this->table . '` WHERE `' . $this->primaryKey . '` = :value';

        $parameters = [':value' => $value];

        $query = $this->query($query, $parameters);

        return $query->fetch();
    }

    public function find($column, $value)
    {
        $query = 'SELECT * FROM `' . $this->table . '` WHERE `' . $column . '` = :value';

        $parameters = ['value' => $value];

        $query = $this->query($query, $parameters);

        return $query->fetchAll();
    }

    public function total()
    {
        $query = 'SELECT COUNT(*) FROM `' . $this->table . '`';

        $query = $this->query($query);

        $row = $query->fetch();

        return $row[0];
    }

    public function save(array $record)
    {
        try {
            if ($record[$this->primaryKey] == '') {
                $record[$this->primaryKey] = null;
            }
            $this->insert($record);
        } catch (\PDOException $e) {
            $this->update($record);
        }
    }
}
