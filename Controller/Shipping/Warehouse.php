<?php

declare(strict_types=1);

namespace Dorn\Novaposhta\Controller\Shipping;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Warehouse implements HttpGetActionInterface
{
    public function __construct(
        private \Dorn\Novaposhta\Ui\Checkout\Warehouse\DescriptionDataProvider $dataProvider,
        private \Magento\Framework\Controller\ResultFactory $resultFactory
    ) {
    }

    public function execute()
    {
        try {
            $result = [
                'data'    => $this->dataProvider->getData(),
                'success' => true
            ];
        } catch (\Exception $e) {
            $result['errorMessage'] = $e->getMessage();
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)
                                   ->setData($result);
    }
}
