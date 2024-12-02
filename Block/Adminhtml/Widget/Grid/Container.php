<?php
/**
 * Copyright © Heliton. All rights reserved.
 */

namespace Heliton\ReservationAdminGrid\Block\Adminhtml\Widget\Grid;

use Magento\Backend\Block\Widget\Grid\Container as GridContainer;

class Container extends GridContainer
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->removeButton('add');
    }
}
