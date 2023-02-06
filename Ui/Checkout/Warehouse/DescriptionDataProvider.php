<?php

declare(strict_types=1);

namespace Dorn\Novaposhta\Ui\Checkout\Warehouse;

class DescriptionDataProvider
{
    public function __construct(
        private \Magento\Framework\App\RequestInterface $request,
        private \Dorn\Novaposhta\Api\AddressManagementInterface $management
    ) {
    }

    public function getData():array
    {
        $cityRef = $this->request->getParam('cityRef');
        $query = $this->request->getParam('query');
        $limit = (int) $this->request->getParam('limit');

        $searchResult = $this->management->searchWarehouseByString($cityRef, $query, $limit);

        return array_map(
            static fn($elem) => $elem['Description'],
            $searchResult['data']
        );
    }
}