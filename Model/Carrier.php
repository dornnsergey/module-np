<?php

declare(strict_types=1);

namespace Dorn\Novaposhta\Model;

use Magento\Quote\Model\Quote\Address\RateRequest;

class Carrier extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements \Magento\Shipping\Model\Carrier\CarrierInterface
{
    protected $_code = 'novaposhta';

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        private \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        private \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    public function collectRates(RateRequest $request)
    {
        if (! $this->getConfigFlag('active')) {
            return false;
        }

        $result = $this->rateResultFactory->create();
        $method1 = $this->rateMethodFactory->create();

        $method1->setCarrier($this->_code);
        $method1->setCarrierTitle($this->getConfigData('title'));

        $method1->setMethod($this->_code);
        $method1->setMethodTitle(__('У Відділення, Адресна'));

        $result->append($method1);

        return $result;
    }

    public function getAllowedMethods(): array
    {
        return [$this->_code => $this->getConfigData('name')];
    }
}