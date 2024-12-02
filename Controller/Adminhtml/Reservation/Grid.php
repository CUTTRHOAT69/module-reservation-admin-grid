<?php
/**
 * Copyright © Heliton. All rights reserved.
 */

declare(strict_types=1);

namespace Heliton\ReservationAdminGrid\Controller\Adminhtml\Reservation;

use Heliton\ReservationAdminGrid\Controller\Adminhtml\Index as AdminIndex;

class Grid extends AdminIndex
{
    /**
     * @return void
     */
    public function execute(): void
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
