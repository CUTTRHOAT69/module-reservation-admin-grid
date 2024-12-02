<?php
/**
 * Copyright © Heliton. All rights reserved.
 */

declare(strict_types=1);

namespace Heliton\ReservationAdminGrid\Controller\Adminhtml;

use Magento\Backend\App\Action;

abstract class Index extends Action
{
    public const ADMIN_RESOURCE = 'Heliton_ReservationAdminGrid::index';

    /**
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed(static::ADMIN_RESOURCE);
    }
}
