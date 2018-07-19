<?php declare(strict_types = 1);

namespace Test\SkyEng;

interface DataProviderContract
{
    public function getResponse(array $request): array;
}