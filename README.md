[![Travis](https://travis-ci.com/Kirouane/imposter.svg?branch=master)](https://travis-ci.com/Kirouane/imposter.svg?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/Kirouane/imposter/badge.svg)](https://coveralls.io/github/Kirouane/imposter?branch=master)

Imposter
======

Imposter is a php library that used to serve http stubs and mocks.

Here is an example to emphasize how is simple to mock an HTTP endpoint with this library in PHPUnit.

```php

namespace Imposter;

use PHPUnit\Framework\TestCase;

/**
 * Class ScenarioTest
 * @package Imposter
 */
class ReadMeTest extends TestCase
{
    /**
     * @test
     *
     */
    public function match()
    {
        ImposterFactory::get()->mock(8081)
            ->withPath('/users/1')
            ->withMethod('POST')
            ->returnBody('{"response" :"okay"}')
            ->once()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:8081/users/1')->getBody()->getContents();
        self::assertSame($response, '{"response" :"okay"}');
    }

    public function tearDown()
    {
        ImposterFactory::get()->close();
    }
}

```

Install
==

`composer require kirouane/imposter --dev`

Features
==

## Display logs

In case of the HTTP request doesn't match any mock, you can find out the reason here [http://localhost:2424/mock/log/html](http://localhost:2424/mock/log/html)

Below, you can see what the logs page looks like.

![Logs](doc/log.png) 

## PHPUnit Asserter

Imposter Library uses PHPunit asserters to match HTTP requests with the mocks you create.

Example :

```php
namespace Imposter;

use PHPUnit\Framework\TestCase;

/**
 * Class ScenarioTest
 * @package Imposter
 */
class ReadMeTest extends TestCase
{
    /**
     * @test
     */
    public function match()
    {
        ImposterFactory::get()->mock(8081)
            ->withPath('/users/1')
            ->withMethod(new RegularExpression('/POST|PUT/'))
            ->returnBody('{"response" :"okay"}')
            ->twice()
            ->send();

        $client   = new \GuzzleHttp\Client();
        $response = $client->post('http://localhost:8081/users/1')->getBody()->getContents();
        self::assertSame($response, '{"response" :"okay"}');

        $response = $client->put('http://localhost:8081/users/1')->getBody()->getContents();
        self::assertSame($response, '{"response" :"okay"}');
    }

    public function tearDown()
    {
        ImposterFactory::get()->close();
    }

}
```


## Proxies

Not implemented yet