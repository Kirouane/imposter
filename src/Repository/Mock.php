<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/10/18
 * Time: 12:47 PM
 */

namespace Imposter\Repository;


use Imposter\Db;
use Imposter\Imposter\Matcher;
use Psr\Http\Message\ServerRequestInterface;

class Mock
{
    private $data = [];

    public function recreate()
    {
        $this->data = [];
    }

    public function insert(\Imposter\Model\Mock $mock)
    {
        $mock->setId(uniqid('', true));
        $this->data[$mock->getId()] = $mock;
        return $mock;
    }

    public function findById($id)
    {
        return $this->data[$id] ?? null;
    }

    public function update(\Imposter\Model\Mock $row)
    {
        $this->data[$row->getId()] = $row;
    }

    public function drop()
    {
        $this->data = [];
    }

    public function matchRequest(ServerRequestInterface $request)
    {
        /** @var \Imposter\Model\Mock $mock */
        foreach ($this->data as $mock) {
            $matcher = new Matcher($mock);
            if ($matcher->match($request)) {
                return $mock;
            }
        }

        return null;
    }
}