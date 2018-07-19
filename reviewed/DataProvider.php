<?php declare(strict_types = 1);

namespace Test\SkyEng;

class DataProvider implements DataProviderContract
{
    private $host;
    private $user;
    private $password;

    public function __construct(string $host, string $user, string $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    public function getResponse(array $request): array
    {
        // todo: return built response
    }
}