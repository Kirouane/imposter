<?php

namespace Imposter;
use Lazer\Classes\Database as Lazer;

/**
 * Class Db
 * @package Imposter
 */
class Db
{
    /**
     * Db constructor.
     */
    public function __construct($path = null)
    {
        if (!\defined('LAZER_DATA_PATH')) {
            \define('LAZER_DATA_PATH', realpath(dirname(__DIR__)) . '/data/');
            //\define('LAZER_DATA_PATH', $path);
        }
    }

    /**
     * @param string $tableName
     */
    public function dropTable(string $tableName)
    {
        if ($this->exists($tableName)) {
            Lazer::remove('mock');
        }
    }

    /**
     * @param string $name
     * @param array $fields
     * @throws \Lazer\Classes\LazerException
     */
    public function createTable(string $name, array $fields)
    {
        Lazer::create($name, $fields);
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
        try{
            \Lazer\Classes\Helpers\Validate::table($tableName)->exists();
            return true;
        } catch(\Lazer\Classes\LazerException $e){
            return false;
        }
    }

    public function newRow(string $name)
    {
        return Lazer::table($name);
    }

    public function findById(string $name, $id)
    {
        return Lazer::table($name)->find($id);
    }

    public function select(string $name)
    {
        return Lazer::table($name);
    }
}