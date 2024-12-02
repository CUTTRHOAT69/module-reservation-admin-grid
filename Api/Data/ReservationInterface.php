<?php
/**
 * Copyright © Heliton. All rights reserved.
 */

declare(strict_types=1);

namespace Heliton\ReservationAdminGrid\Api\Data;

/**
 * @api
 */
interface ReservationInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const RESERVATION_ID = 'reservation_id';
    const STOCK_ID = 'stock_id';
    const SKU = 'sku';
    const QUANTITY = 'quantity';
    const METADATA = 'metadata';

    /**
     * Get Reservation Id
     *
     * @return int|null
     */
    public function getReservationId(): ?int;

    /**
     * set Reservation Id
     *
     * @return int|null
     */
    public function setReservationId(?int $reservationId) ;

    /**
     * Get Stock Id
     *
     * @return int
     */
    public function getStockId(): int;

    /**
     * set Stock Id
     *
     * @return int
     */
    public function setStockId(int $stockId);


    /**
     * Get Product SKU
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * set Product SKU
     *
     * @return string
     */
    public function setSku(string $sku);

    /**
     * Get Product Qty
     *
     * This value can be positive (>0) or negative (<0) depending on the Reservation semantic.
     *
     * For example, when an Order is placed, a Reservation with negative quantity is appended.
     * When that Order is processed and the SourceItems related to ordered products are updated, a Reservation with
     * positive quantity is appended to neglect the first one.
     *
     * @return float
     */
    public function getQuantity(): float;

    public function setQuantity(float $qty);

    /**
     * Get Reservation Metadata
     *
     * Metadata is used to store serialized data that encapsulates the semantic of a Reservation.
     *
     * @return string|null
     */
    public function getMetadata(): ?string;

    public function setMetadata(?string $metadata);
}
