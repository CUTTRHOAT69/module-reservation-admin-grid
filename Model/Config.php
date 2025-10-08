<?php
/**
 * Copyright © Heliton. All rights reserved.
 */

declare(strict_types=1);

namespace Heliton\ReservationAdminGrid\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const CONFIG_ORDER_STATUSES = 'h_reservation/general/order_statuses';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {}

    /**
     * @return string|null
     */
    public function getOrderStatuses($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_ORDER_STATUSES,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}