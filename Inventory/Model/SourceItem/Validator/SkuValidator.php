<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Inventory\Model\SourceItem\Validator;

use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;
use Magento\InventoryApi\Api\Data\SourceItemInterface;

/**
 * Check that sku is valid
 */
class SkuValidator implements SourceItemValidatorInterface
{
    /**
     * @var ValidationResultFactory
     */
    private $validationResultFactory;

    /**
     * @param ValidationResultFactory $validationResultFactory
     */
    public function __construct(ValidationResultFactory $validationResultFactory)
    {
        $this->validationResultFactory = $validationResultFactory;
    }

    /**
     * @inheritdoc
     */
    public function validate(SourceItemInterface $source): ValidationResult
    {
        $errors = [];
        if ('' === trim((string)$source->getSku())) {
            $errors[] = __(
                '"%field" can not be empt.',
                ['field' => SourceItemInterface::SKU]
            );
        }

        return $this->validationResultFactory->create(['errors' => $errors]);
    }
}
