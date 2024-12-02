<?php
/**
 * Copyright © Heliton. All rights reserved.
 */

declare(strict_types=1);

namespace Heliton\ReservationAdminGrid\Api;

use Heliton\ReservationAdminGrid\Api\Data\ReservationInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @api
 */
interface ReservationRepositoryInterface
{
    /**
     * @param ReservationInterface $reservation
     * @return ReservationInterface
     * @throws CouldNotSaveException
     */
    public function save(ReservationInterface $reservation): ReservationInterface;

    /**
     * @param int $reservationId
     * @return ReservationInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $reservationId): ReservationInterface;

    /**
     * @param SearchCriteriaInterface $criteria
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * @param ReservationInterface $reservation
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ReservationInterface $reservation): bool;

    /**
     * @param int $reservationId
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $reservationId): bool;
}
