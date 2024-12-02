<?php
/**
 * Copyright © Heliton. All rights reserved.
 */

/** @noinspection PhpUndefinedClassInspection */

declare(strict_types=1);

namespace Heliton\ReservationAdminGrid\Controller\Adminhtml\Reservation;

use Heliton\ReservationAdminGrid\Controller\Adminhtml\Index as AdminIndex;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends AdminIndex
{
    public function __construct(
        Context $context,
        protected PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
    }

    /**
     * @return ResultInterface|ResponseInterface|Page|null
     */
    public function execute()
    {
        if ($this->getRequest()->getParam('ajax')) {
            $this->_forward('grid');
            return null;
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Heliton_ReservationAdminGrid::general');
        $resultPage->addBreadcrumb(__('Reservations'), __('Reservations'));
        $resultPage->getConfig()->getTitle()->prepend(__('Reservations List'));

        return $resultPage;
    }
}
