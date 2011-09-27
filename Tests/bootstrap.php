<?php
spl_autoload_register(function($class)
{
    $class = ltrim($class, '\\');
    if (0 === strpos($class, 'Hypebeast\\WordpressBundle')) {
        $path = implode('/', array_slice(explode('\\', $class), 2)) . '.php';
        require_once __DIR__ . '/../' . $path;
    }
});

require_once $_SERVER['SYMFONY'].'/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony' => $_SERVER['SYMFONY'],
    'Doctrine\\Common' => $_SERVER['DOCTRINE_COMMON'],
    'Doctrine' => $_SERVER['DOCTRINE_ORM'],
));
$loader->register();