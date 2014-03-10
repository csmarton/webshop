<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('FOS', __DIR__.'/../vendor/bundles');
$loader->add('Assetic', __DIR__.'/../vendor/assetic/src');
$loader->add('Gregwar', __DIR__.'/../vendor/bundles');
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
