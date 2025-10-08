<?php
/**
 * Copyright © Heliton. All rights reserved.
 */

declare(strict_types=1);

namespace Heliton\ReservationAdminGrid\Block\Adminhtml\Incorrect;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data as BackendHelper;
use Heliton\ReservationAdminGrid\Model\ResourceModel\Reservation\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Heliton\ReservationAdminGrid\Model\Config;
use Magento\Backend\Block\Widget\Grid as MagentoGrid;
use \Zend_Db_Select;
use \Zend_Db_Expr;

class Grid extends MagentoGrid
{
    const MAGENTO_INCREMENT_ID_COLUMN = 'increment_id';
    const MAGENTO_QUANTITY_COLUMN = 'quantity';
    const MAGENTO_STOCK_ID_COLUMN = 'stock_id';
    const MAGENTO_METADATA_COLUMN = 'metadata';
    const MAGENTO_STATUS_COLUMN = 'status';
    const MAGENTO_SKU_COLUMN = 'sku';

    const MAGENTO_METADATA_INCREMENT_ID_KEY = 'object_increment_id';

    public function __construct(
        protected Context $context,
        protected BackendHelper $backendHelper,
        protected CollectionFactory $collectionFactory,
        protected OrderCollectionFactory $orderCollectionFactory,
        protected Config $config,
        protected array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _prepareCollection()
    {
        $potentialIncorrectOrdersIds = $this->getPotentialIncorrectOrdersIds();
        $invalidOrdersIds = $this->getIncorrectOrdersIdsByFinalStatus($potentialIncorrectOrdersIds);
        $invalidOrdersIds = $this->getNonExistingOrdersWithReservations($potentialIncorrectOrdersIds, $invalidOrdersIds);
        $invalidReservationCollection = $this->createInvalidReservationCollection($invalidOrdersIds);

        $this->setCollection($invalidReservationCollection);

        return parent::_prepareCollection();
    }

    private function getIncorrectOrdersIdsByFinalStatus($potentialIncorrectOrdersIds): array
    {
        $finalStatuses = $this->config->getOrderStatuses();

        $orderCollection = $this->orderCollectionFactory->create()
            ->addFieldToFilter(self::MAGENTO_INCREMENT_ID_COLUMN, ['in' => array_values($potentialIncorrectOrdersIds)])
            ->addFieldToFilter(self::MAGENTO_STATUS_COLUMN, ['in' => $finalStatuses]);

        $invalidOrdersIds = [];
        foreach ($orderCollection as $order) {
            $invalidOrdersIds[] = $order->getData(self::MAGENTO_INCREMENT_ID_COLUMN);
        }

        return $invalidOrdersIds;
    }

    private function getPotentialIncorrectOrdersIds(): array
    {
        $collection = $this->collectionFactory->create();

        $select = $collection->getSelect();
        $select->reset(Zend_Db_Select::COLUMNS)
            ->columns([
                self::MAGENTO_SKU_COLUMN => self::MAGENTO_SKU_COLUMN,
                self::MAGENTO_STOCK_ID_COLUMN => self::MAGENTO_STOCK_ID_COLUMN,
                self::MAGENTO_QUANTITY_COLUMN => new Zend_Db_Expr('SUM('. self::MAGENTO_QUANTITY_COLUMN .')'),
                self::MAGENTO_METADATA_COLUMN => new Zend_Db_Expr('GROUP_CONCAT(' . self::MAGENTO_METADATA_COLUMN . ')')
            ])
            ->group([self::MAGENTO_SKU_COLUMN, self::MAGENTO_STOCK_ID_COLUMN])
            ->having('SUM(' . self::MAGENTO_QUANTITY_COLUMN . ') != 0');

        $incrementIds = [];
        foreach ($collection as $item) {
            $metadataConcat = $item->getData(self::MAGENTO_METADATA_COLUMN);

            $explodedMetadata = explode('{', $metadataConcat);
            foreach ($explodedMetadata as $metadataJson) {
                $fixedMetadataJson = rtrim($metadataJson, ',');
                $meta = json_decode("{" . $fixedMetadataJson, true);
                if (!empty($meta[self::MAGENTO_METADATA_INCREMENT_ID_KEY])) {
                    $incrementIds[] = $meta[self::MAGENTO_METADATA_INCREMENT_ID_KEY];
                }
            }
        }

        return $incrementIds;
    }

    private function getNonExistingOrdersWithReservations($potentialIncorrectOrdersIds, $invalidOrdersIds): array
    {
        $existingIds = $this->getExistingIncrementIds($potentialIncorrectOrdersIds);
        $notExistingIds = array_diff($potentialIncorrectOrdersIds, $existingIds);
        return array_merge($invalidOrdersIds, $notExistingIds);
    }

    private function getExistingIncrementIds(array $incrementIds): array
    {
        $collection = $this->orderCollectionFactory->create();
        $collection->addFieldToFilter(self::MAGENTO_INCREMENT_ID_COLUMN, ['in' => $incrementIds]);

        $existingIds = $collection->getColumnValues(self::MAGENTO_INCREMENT_ID_COLUMN);

        return $existingIds;
    }

    private function createInvalidReservationCollection($invalidOrdersIds)
    {
        $invalidReservationCollection = $this->collectionFactory->create();

        $invalidReservationCollection->getSelect()->reset(Zend_Db_Select::COLUMNS)
            ->columns([
                self::MAGENTO_SKU_COLUMN => self::MAGENTO_SKU_COLUMN,
                self::MAGENTO_STOCK_ID_COLUMN => self::MAGENTO_STOCK_ID_COLUMN,
                self::MAGENTO_QUANTITY_COLUMN => new Zend_Db_Expr('SUM('. self::MAGENTO_QUANTITY_COLUMN .')'),
                self::MAGENTO_METADATA_COLUMN => new Zend_Db_Expr('GROUP_CONCAT(' . self::MAGENTO_METADATA_COLUMN . ')')
            ])
            ->group([self::MAGENTO_SKU_COLUMN, self::MAGENTO_STOCK_ID_COLUMN])
            ->having('SUM(' . self::MAGENTO_QUANTITY_COLUMN . ') != 0');

        $whereStatement = "";
        foreach ($invalidOrdersIds as $invalidOrderIncrementId) {
            $whereStatement = $whereStatement . self::MAGENTO_METADATA_COLUMN . ' LIKE '.'"%' . $invalidOrderIncrementId . '%" OR ';
        }

        $whereStatement = rtrim($whereStatement, 'OR ');
        $invalidReservationCollection->getSelect()->where($whereStatement);

        if (!$invalidOrdersIds) {
            $invalidReservationCollection->getItems();
            // $invalidReservationCollection->getSize() will still return a value, seems like a Magento bug that doesn't clear the size value after removing the items
            $invalidReservationCollection->removeAllItems();
        }

        return $invalidReservationCollection;
    }
}