<?php declare(strict_types = 1);

namespace Test\SkyEng;

use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

$request = [];
$host = '';
$user = '';
$password = '';

/** @var CacheItemPoolInterface $cacheItemPool */
$cacheItemPool = new CacheItemPool();
/** @var LoggerInterface $logger */
$logger = new Logger();

$dataProvider = new DataProvider($host, $user, $password);
$cacheDecorator = (new CachedDataProvider($dataProvider, $cacheItemPool))->setCacheTtlInterval('+1 day');
$loggerDecorator = new LoggerDataProvider($cacheDecorator, $logger);

try {
    $loggerDecorator->getResponse($request);
} catch (Exception $exception) {
    // todo решаем, что делать с ошибкой при получении данных
}