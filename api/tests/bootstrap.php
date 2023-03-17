<?php

use App\Kernel;
use Quiz\Tests\Shared\Infrastructure\PhpUnit\AggregateRootComparator;
use Quiz\Tests\Shared\Infrastructure\PhpUnit\EventComparator;
use SebastianBergmann\Comparator\Factory as ComparatorFactory;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists(dirname(__DIR__) . '/config/bootstrap.php')) {
    require dirname(__DIR__) . '/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

$comparatorFactory = ComparatorFactory::getInstance();
$comparatorFactory->reset();
$comparatorFactory->register(new AggregateRootComparator());
$comparatorFactory->register(new EventComparator());
