<?php

namespace src\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

class DecoratorManager extends DataProvider
{
    public $cache; // излишняя область видимости, достаточно protected
    public $logger; // излишняя область видимости, достаточно protected

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param CacheItemPoolInterface $cache
     */
    public function __construct($host, $user, $password, CacheItemPoolInterface $cache) // избыточное кол-во параметров, host, user, password можно заключить в поля класса DSN, например
    {
        parent::__construct($host, $user, $password);
        $this->cache = $cache;

        // следует определить логгер по-умолчанию или не использовать логирование если он не определен.
        // Или вариант: передавать логгер в конструктор
    }

    public function setLogger(LoggerInterface $logger) // добавить :void
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(array $input) // добавить тип возвращаемого значения
    {
        try {
            $cacheKey = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = parent::get($input);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify('+1 day') // время жизни лучше передавать параметром или в DataProvider определить TTL для хранения данных
                );

            return $result;
        } catch (Exception $e) {
            $this->logger->critical('Error');
        }

        return [];
    }

    public function getCacheKey(array $input) // добавить тип возвращаемого значения, область видимости можно сузить до private
    {
        return json_encode($input); // длина ключ может быть избыточна для кеш, лучше использовать длину ключа меньше, например,
                                    // используя md5 или другие хеш функции
    }
}

/**
 * архитектура построение декоратора не удовлетворяет изначальной концепции патерна
 * Нарушение принципа единственной ответственности: логирование и кеширование
 */