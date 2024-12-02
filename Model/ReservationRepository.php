<?php
/**
 * Copyright © Heliton. All rights reserved.
 */

/** @noinspection PhpUndefinedClassInspection */

declare(strict_types=1);

namespace Heliton\ReservationAdminGrid\Model;

use Heliton\ReservationAdminGrid\Api\Data\ReservationInterface;
use Heliton\ReservationAdminGrid\Api\ReservationRepositoryInterface;
use Heliton\ReservationAdminGrid\Model\ReservationFactory;
use Heliton\ReservationAdminGrid\Model\ResourceModel\Reservation as ReservationResourceModel;
use Heliton\ReservationAdminGrid\Model\ResourceModel\Reservation\CollectionFactory as ReservationCollectionFactory;
use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SortOrder;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function __construct(
        private readonly ReservationFactory $reservationModelFactory,
        private readonly ReservationResourceModel $reservationResourceModel,
        private readonly ReservationCollectionFactory $reservationCollectionFactory,
        private readonly SearchResultsInterfaceFactory $searchResultsFactory
    ) {}

    /**
     * @inheritDoc
     */
    public function save(ReservationInterface $reservation): ReservationInterface
    {
        try {
            $this->reservationResourceModel->save($reservation);
        } catch (Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $reservation;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $reservationId): ReservationInterface
    {
        $reservation = $this->reservationModelFactory->create();
        $this->reservationResourceModel->load($reservation, $reservationId, "reservation_id");

        if (!$reservation->getId()) {
            throw new NoSuchEntityException(__('Reservation with id "%1" does not exist.', $reservationId));
        }

        return $reservation;
    }

    /**
     * @inheritDoc
     */
    public function delete(ReservationInterface $reservation): bool
    {
        try {
            $this->reservationResourceModel->delete($reservation);
        } catch (Exception $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $reservationId): bool
    {
        return $this->delete($this->getById($reservationId));
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $collection = $this->reservationCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }
            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $objects = [];
        foreach ($collection as $objectModel) {
            $objects[] = $objectModel;
        }
        $searchResults->setItems($objects);
        return $searchResults;
    }
}
