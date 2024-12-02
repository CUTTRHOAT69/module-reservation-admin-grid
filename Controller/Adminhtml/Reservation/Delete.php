<?php
/**
 * Copyright © Heliton. All rights reserved.
 */

/** @noinspection PhpUndefinedClassInspection */

declare(strict_types=1);

namespace Heliton\ReservationAdminGrid\Controller\Adminhtml\Reservation;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Psr\Log\LoggerInterface;
use Heliton\ReservationAdminGrid\Model\ReservationRepository;

class Delete extends Action
{
    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        private readonly CartRepositoryInterface $quoteRepository,
        private readonly LoggerInterface $logger,
        private readonly ReservationRepository $reservationRepository
    ) {
        parent::__construct($context);
        $this->resultFactory = $resultFactory;
    }

    /**
     * Deletes a reservation by ID, logs the deletion information, and handles exceptions accordingly.
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var Magento\Backend\Model\View\Result\Redirect\Interceptor $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        $reservationId = (int) $this->getRequest()->getParam('reservation_id');

        if (!$reservationId) {
            return $resultRedirect->setPath('*/*/index');
        }

        try {
            $reservation = $this->reservationRepository->getById($reservationId);
            $this->reservationRepository->deleteById($reservationId);

            $this->messageManager->addSuccessMessage(__("You have deleted the reservation '%1'. A log has been saved with the information.", $reservationId));

            $this->logger->info(
                'Reservation deleted:',
                [
                    'user_id' => $this->_auth->getUser()->getId(),
                    'user_name' => $this->_auth->getUser()->getUserName(),
                    'quote_id' => $reservationId,
                    'deleted_at' => date('Y-m-d H:i:s'),
                    'data' => $reservation->getData()
                ]
            );
        } catch (\Exception $e) {
            $this->logger->info("Error deleting reservation", [$e->getMessage()]);
            $this->messageManager->addExceptionMessage($e, __("We can't delete that reservation."));
        }

        return $resultRedirect->setPath('*/*/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Heliton_ReservationAdminGrid::delete');
    }
}
