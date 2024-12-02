<?php
/**
 * Copyright © Heliton. All rights reserved.
 */

declare(strict_types=1);

namespace Heliton\ReservationAdminGrid\Block\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;

class Metadata extends AbstractRenderer
{
    /**
     * Renders grid column
     *
     * @param   DataObject $row
     * @return  string
     */
    public function render(DataObject $row)
    {
        $jsonMetadata = $row->getMetadata();
        $metadatas = json_decode($jsonMetadata,true);
        $concatMetadata = "";
        foreach($metadatas as $field => $value) {
            $concatMetadata .= "{$field}: {$value}";
            if (array_key_last($metadatas) == $field) {
                continue;
            }
            $concatMetadata .= " - ";
        }
        return $concatMetadata;
    }
}