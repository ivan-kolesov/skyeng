<?php declare(strict_types = 1);

namespace Test\SkyEng;

use DateTime;
use Psr\Cache\CacheItemPoolInterface;

class CachedDataProvider implements DataProviderContract
{
    private $dataProvider;
    private $cache;
    private $cacheTtlInterval = self::CACHE_TTL_INTERVAL_DEFAULT;

    private const CACHE_PREFIX = 'data-provider';
    private const CACHE_TTL_INTERVAL_DEFAULT = '+1 day';

    public function __construct(DataProviderContract $dataProvider, CacheItemPoolInterface $cache)
    {
        $this->dataProvider = $dataProvider;
        $this->cache = $cache;
    }

    public function setCacheTtlInterval(string $interval): self
    {
        $this->cacheTtlInterval = $interval;
        return $this;
    }

    public function getResponse(array $request): array
    {
        $cacheKey = $this->getCacheKey($request);
        $cacheItem = $this->cache->getItem($cacheKey);
        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $result = $this->dataProvider->getResponse($request);

        $cacheItem
            ->set($result)
            ->expiresAt(
                (new DateTime())->modify($this->cacheTtlInterval)
            );

        return $result;
    }

    private function getCacheKey(array $request): string
    {
        return static::CACHE_PREFIX . ':' . md5(serialize($request));
    }
}