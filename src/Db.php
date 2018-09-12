<?php

namespace Imposter;
use Lazer\Classes\Database as Lazer;

/**
 * Class Db
 * @package Imposter
 */
class Db
{

    private $data = [];

    /**
     * Db constructor.
     */
    public function __construct($path = null)
    {

    }

    /**
     * @param string $tableName
     */
    public function dropTable(string $tableName)
    {
        if ($this->exists($tableName)) {
            unset($this->data[$tableName]);
        }
    }

    /**
     * @param string $name
     * @param array $fields
     * @throws \Lazer\Classes\LazerException
     */
    public function createTable(string $name, array $fields)
    {
        $this->data[$name] = [];
    }

    /**
     * @param string $name
     * @param array $fields
     * @throws \Lazer\Classes\LazerException
     */
    public function recreate(string $name, array $fields)
    {
        $this->dropTable($name);
        $this->createTable($name, $fields);
    }

    /**
     * @param string $tableName
     * @return bool
     */
    public function exists(string $tableName): bool
    {
        return isset($this->data[$tableName]);
    }

    public function newRow(string $name, $fields)
    {
        $row = (object)$fields;
        $this->data[$name][$row->id] = $row;
        return $row;
    }

    public function findById(string $name, $id)
    {
        foreach ($this->data[$name] as $row) {
            if ($row->id === $id) {
                return $row;
            }
        }

        return null;
    }

    public function findByFields(string $name, $fields)
    {
        foreach ($this->data[$name] as $row) {
            $found = true;
            foreach ($fields as $field => $value) {

                if ($row->$field !== $value) {
                    $found = false;
                }
            }

            if ($found) {
                return $row;
            }
        }

        return null;
    }

    public function saveRow($name, $row)
    {

        $this->data[$name][$row->id] = $row;

    }
}