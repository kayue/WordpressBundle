<?php
spl_autoload_register(function($class)
{
    $class = ltrim($class, '\\');
    if (0 === strpos($class, 'Hypebeast\\WordpressBundle')) {
        $path = implode('/', array_slice(explode('\\', $class), 2)) . '.php';
        require_once __DIR__ . '/../' . $path;
    }
});

foreach (array('SYMFONY', 'DOCTRINE_COMMON', 'DOCTRINE_ORM') as $component) {
  if (!isset($_SERVER[$component])) {
    throw new \RuntimeException("You must set the {$component} path");
  }

  if (0 === strpos($_SERVER[$component], '..')) {
    # The path is relative to phpunit.xml, so get the absolute path
    $_SERVER[$component] = realpath(__DIR__ . "/../{$_SERVER[$component]}");
  }
}

require_once $_SERVER['SYMFONY'].'/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony' => $_SERVER['SYMFONY'],
    'Doctrine\\Common' => $_SERVER['DOCTRINE_COMMON'],
    'Doctrine' => $_SERVER['DOCTRINE_ORM'],
));
$loader->register();