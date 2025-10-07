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
        $metadatas = json_decode($jsonMetadata, true);

        if ($metadatas == null) {
            return $this->manageMultipleMetadatas($jsonMetadata);
        }

        return $this->manageSingleMetadata($metadatas);
    }

    private function manageMultipleMetadatas($jsonMetadata) {
        $multipleMetadatas = [];
        $explodedMetadata = explode('{', $jsonMetadata);
        foreach ($explodedMetadata as $metadataJson) {
            $fixedMetadataJson = rtrim($metadataJson, ',');
            $concatMetadatas = json_decode("{" . $fixedMetadataJson, true);
            if ($concatMetadatas) {
                $multipleMetadatas[] = $concatMetadatas;
            }
        }

        $finalConcatMetadata = "";
        foreach ($multipleMetadatas as $metadatas) {
            $concatMetadata = $this->manageSingleMetadata($metadatas);
            $finalConcatMetadata = $finalConcatMetadata . $concatMetadata . " | ";
        }

        return $finalConcatMetadata;
    }

    private function manageSingleMetadata($metadatas) {
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