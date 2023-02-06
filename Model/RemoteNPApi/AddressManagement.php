<?php

declare(strict_types=1);

namespace Dorn\Novaposhta\Model\RemoteNPApi;

use Magento\Framework\Exception\LocalizedException;

class AddressManagement implements \Dorn\Novaposhta\Api\AddressManagementInterface
{
    private string $apiUrl = 'https://api.novaposhta.ua/v2.0/json/';

    private const API_KEY = 'carriers/novaposhta/apikey';

    public function __construct(
        private \Magento\Framework\App\Config\ScopeConfigInterface $config,
        private \Magento\Framework\HTTP\Client\Curl $curl,
        private \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function searchSettlement(string $name, int $limit = 1): array
    {
        $params = $this->serializer->serialize([
            'apiKey'           => $this->config->getValue(self::API_KEY),
            'modelName'        => 'Address',
            'calledMethod'     => 'searchSettlements',
            'methodProperties' => [
                'CityName' => $name,
                'Limit'    => $limit
            ]
        ]);

        $this->curl->post($this->apiUrl, $params);
        $response = $this->serializer->unserialize($this->curl->getBody());

        if (! empty($response['errors'])) {
            throw new LocalizedException(__($response['errors'][0]));
        }

        return $response;
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function searchWarehouseByString(string $cityRef, string $query, int $limit = 1): array
    {
        $params = $this->serializer->serialize([
            'apiKey'           => $this->config->getValue(self::API_KEY),
            'modelName'        => 'Address',
            'calledMethod'     => 'getWarehouses',
            'methodProperties' => [
                'CityRef'      => $cityRef,
                'FindByString' => $query,
                'Limit'        => $limit
            ]
        ]);

        $this->curl->post($this->apiUrl, $params);
        $response = $this->serializer->unserialize($this->curl->getBody());

        if (! empty($response['errors'])) {
            throw new LocalizedException(__($response['errors'][0]));
        }

        return $response;
    }
}