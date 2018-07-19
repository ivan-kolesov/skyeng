<?php declare(strict_types = 1);

namespace Test\SkyEng;

use Exception;
use Psr\Log\LoggerInterface;

class LoggerDataProvider implements DataProviderContract
{
    private $dataProvider;
    private $logger;

    public function __construct(DataProviderContract $dataProvider, LoggerInterface $logger)
    {
        $this->dataProvider = $dataProvider;
        $this->logger = $logger;
    }

    /**
     * @param array $request
     * @return array
     * @throws Exception
     */
    public function getResponse(array $request): array
    {
        try {
            return $this->dataProvider->getResponse($request);
        } catch (Exception $exception) {
            $this->logger->critical('Error', $exception->getMessage());

            throw $exception;
        }
    }
}