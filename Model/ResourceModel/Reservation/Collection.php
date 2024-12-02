<?php
/**
 * Copyright © Heliton. All rights reserved.
 */

declare(strict_types=1);

namespace Heliton\ReservationAdminGrid\Model\ResourceModel\Reservation;

use Heliton\ReservationAdminGrid\Model\Reservation;
use Heliton\ReservationAdminGrid\Model\ResourceModel\Reservation as ReservationResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'reservation_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'inventory_reservation_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'inventory_reservation_collection';

    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(Reservation::class, ReservationResourceModel::class);
    }
}
