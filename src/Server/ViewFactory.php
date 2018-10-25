<?php
declare(strict_types=1);

namespace Imposter\Server;


use Imposter\Common\Di;
use Imposter\Common\Di\InterfaceFactory;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;

/**
 * Class ViewFactory
 * @package Imposter
 */
class ViewFactory implements InterfaceFactory
{

    /**
     * @param \Imposter\Common\Di $di
     * @return PhpEngine
     */
    public function create(Di $di): PhpEngine
    {
        $filesystemLoader = new FilesystemLoader([__DIR__ . '/Api/Controller/%name%']);

        return new PhpEngine(new TemplateNameParser(), $filesystemLoader);
    }
}