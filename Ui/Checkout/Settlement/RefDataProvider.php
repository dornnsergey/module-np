<?php

declare(strict_types=1);

namespace Dorn\Novaposhta\Ui\Checkout\Settlement;

class RefDataProvider
{
    public function __construct(
        private \Magento\Framework\App\RequestInterface $request,
        private \Dorn\Novaposhta\Api\AddressManagementInterface $management
    ) {
    }

    public function getData(): array
    {
        $name = $this->request->getParam('name');
        $limit = (int) $this->request->getParam('limit');

        $searchResult = $this->management->searchSettlement($name, $limit);

        return array_map(
            static fn($elem) => $elem['DeliveryCity'],
            $searchResult['data'][0]['Addresses']
        );
    }
}