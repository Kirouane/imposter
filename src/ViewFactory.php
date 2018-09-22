<?php
/**
 * Created by PhpStorm.
 * User: nassim.kirouane
 * Date: 9/21/18
 * Time: 5:15 PM
 */

namespace Imposter;


use Imposter\Di\InterfaceFactory;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;

class ViewFactory implements InterfaceFactory
{

    public function create(Di $di)
    {
        $filesystemLoader = new FilesystemLoader([__DIR__ . '/Api/Controller/%name%']);

        return new PhpEngine(new TemplateNameParser(), $filesystemLoader);
    }
}