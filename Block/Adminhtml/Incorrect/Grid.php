<?php
/**
 * Copyright © Heliton. All rights reserved.
 */

declare(strict_types=1);

namespace Heliton\ReservationAdminGrid\Block\Adminhtml\Incorrect;

use Magento\Backend\Block\Widget\Grid as MagentoGrid;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data as BackendHelper;
use Heliton\ReservationAdminGrid\Model\ResourceModel\Reservation\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use \Zend_Db_Select;
use \Zend_Db_Expr;

class Grid extends MagentoGrid
{
    public function __construct(
        protected Context $context,
        protected BackendHelper $backendHelper,
        protected CollectionFactory $collectionFactory,
        protected OrderCollectionFactory $orderCollectionFactory,
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
        $finalStatuses = $this->_scopeConfig->getValue(
            'h_reservation/general/order_statuses',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $orderCollection = $this->orderCollectionFactory->create()
            ->addFieldToFilter('increment_id', ['in' => array_values($potentialIncorrectOrdersIds)])
            ->addFieldToFilter('status', ['in' => $finalStatuses]);

        $invalidOrdersIds = [];
        foreach ($orderCollection as $order) {
            $invalidOrdersIds[] = $order->getData("increment_id");
        }

        return $invalidOrdersIds;
    }

    private function getPotentialIncorrectOrdersIds(): array
    {
        $collection = $this->collectionFactory->create();

        $select = $collection->getSelect();
        $select->reset(Zend_Db_Select::COLUMNS)
            ->columns([
                'sku'      => 'sku',
                'stock_id' => 'stock_id',
                'quantity' => new Zend_Db_Expr('SUM(quantity)'),
                'metadata' => new Zend_Db_Expr('GROUP_CONCAT(metadata)')
            ])
            ->group(['sku', 'stock_id'])
            ->having('SUM(quantity) != 0');

        $incrementIds = [];
        foreach ($collection as $item) {
            $metadataConcat = $item->getData('metadata');

            $explodedMetadata = explode('{', $metadataConcat);
            foreach ($explodedMetadata as $metadataJson) {
                $fixedMetadataJson = rtrim($metadataJson, ',');
                $meta = json_decode("{" . $fixedMetadataJson, true);
                if (!empty($meta['object_increment_id'])) {
                    $incrementIds[] = $meta['object_increment_id'];
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
        $collection->addFieldToFilter('increment_id', ['in' => $incrementIds]);

        $existingIds = $collection->getColumnValues('increment_id');

        return $existingIds;
    }

    private function createInvalidReservationCollection($invalidOrdersIds)
    {
        $invalidReservationCollection = $this->collectionFactory->create();

        $invalidReservationCollection->getSelect()->reset(Zend_Db_Select::COLUMNS)
            ->columns([
                'sku'      => 'sku',
                'stock_id' => 'stock_id',
                'quantity' => new Zend_Db_Expr('SUM(quantity)'),
                'metadata' => new Zend_Db_Expr('GROUP_CONCAT(metadata)')
            ])
            ->group(['sku', 'stock_id'])
            ->having('SUM(quantity) != 0');

        $whereStatement = "";
        foreach ($invalidOrdersIds as $invalidOrderIncrementId) {
            $whereStatement = $whereStatement . 'metadata LIKE '.'"%' . $invalidOrderIncrementId . '%" OR ';
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