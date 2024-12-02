<?php
/**
 * Copyright © Heliton. All rights reserved.
 */

declare(strict_types=1);

namespace Heliton\ReservationAdminGrid\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Reservation extends AbstractDb
{
    /**
     * @return void
     */
	protected function _construct(): void
	{
		$this->_init('inventory_reservation', 'reservation_id');
	}
}
