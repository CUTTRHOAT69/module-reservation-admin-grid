<?php
/**
 * Copyright © Heliton. All rights reserved.
 */
declare(strict_types=1);

namespace Heliton\ReservationAdminGrid\Model;

use Heliton\ReservationAdminGrid\Api\Data\ReservationInterface;
use Magento\Framework\Model\AbstractModel;
use Heliton\ReservationAdminGrid\Model\ResourceModel\Reservation as ReservationResourceModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;

/**
 * {@inheritdoc}
 */
class Reservation extends AbstractModel implements ReservationInterface
{
    public function __construct(
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_init(ReservationResourceModel::class);
    }

    /**
     * @inheritdoc
     */
    public function getReservationId(): ?int
    {
        return $this->getData(ReservationInterface::RESERVATION_ID);
    }

    /**
     * @inheritdoc
     */
    public function setReservationId($reservationId)
    {
        return $this->setData(ReservationInterface::RESERVATION_ID, $reservationId);
    }

    /**
     * @inheritdoc
     */
    public function getStockId(): int
    {
        return $this->getData(ReservationInterface::STOCK_ID);
    }

    /**
     * @inheritdoc
     */
    public function setStockId($stockId)
    {
        return $this->setData(ReservationInterface::STOCK_ID, $stockId);
    }

    /**
     * @inheritdoc
     */
    public function getSku(): string
    {
        return $this->getData(ReservationInterface::SKU);
    }

    /**
     * @inheritdoc
     */
    public function setSku($sku)
    {
        return $this->setData(ReservationInterface::SKU, $sku);
    }

    /**
     * @inheritdoc
     */
    public function getQuantity(): float
    {
        return $this->getData(ReservationInterface::QUANTITY);
    }

    /**
     * @inheritdoc
     */
    public function setQuantity($qty)
    {
        return $this->setData(ReservationInterface::QUANTITY, $qty);
    }

    /**
     * @inheritdoc
     */
    public function getMetadata(): ?string
    {
        return $this->getData(ReservationInterface::METADATA);
    }

    /**
     * @inheritdoc
     */
    public function setMetadata($metada)
    {
        return $this->setData(ReservationInterface::METADATA, $metada);
    }
}
