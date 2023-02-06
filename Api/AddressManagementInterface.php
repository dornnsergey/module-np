<?php

declare(strict_types=1);

namespace Dorn\Novaposhta\Api;

interface AddressManagementInterface
{
    public function searchSettlement(string $name, int $limit = 1): array;

    public function searchWarehouseByString(string $cityRef, string $query, int $limit = 1): array;
}