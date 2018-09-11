<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/10/18
 * Time: 12:47 PM
 */

namespace Imposter\Repository;


use Imposter\Db;

class Mock
{
    private $name = 'mock';
    /**
     * @var Db
     */
    private $db;

    private $fields = [
        'id' => 'integer',
        'port' => 'integer',
        'request_uri_path' => 'string',
        'request_body' => 'string',
        'request_method' => 'string',
        'request_headers' => 'string',
        'request_protocol_version' => 'string',
        'request_uploaded_files' => 'string',

        'response_body' => 'string',
        'response_headers' => 'string',
        'hits' => 'integer'
    ];

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function recreate()
    {
        $this->db->recreate($this->name, $this->fields);
    }

    public function newRow($fields)
    {
        $row = $this->db->newRow($this->name);
        foreach ($fields as $key => $value) {
            $row->$key = $value;
        }

        $row->save();

        return $row;
    }

    public function findById($id)
    {
        return $this->db->findById($this->name, $id);
    }

    public function findByCriteria(array $array)
    {

        return $this->db->select($this->name)->where('request_uri_path', '=', $array['request_uri_path'])->find();
    }
}