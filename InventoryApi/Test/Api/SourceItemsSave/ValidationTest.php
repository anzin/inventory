<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\InventoryApi\Test\Api\SourceItemsSave;

use Magento\Framework\Webapi\Exception;
use Magento\Framework\Webapi\Rest\Request;
use Magento\InventoryApi\Api\Data\SourceItemInterface;
use Magento\TestFramework\TestCase\WebapiAbstract;

class ValidationTest extends WebapiAbstract
{
    /**#@+
     * Service constants
     */
    const RESOURCE_PATH = '/V1/inventory/source-items';
    const SERVICE_NAME = 'inventoryApiSourceItemsSaveV1';
    /**#@-*/

    /**
     * @var array
     */
    private $validData = [
        SourceItemInterface::SKU => 'SKU-1',
        SourceItemInterface::QUANTITY => 1.5,
        SourceItemInterface::SOURCE_ID => 10,
        SourceItemInterface::STATUS => SourceItemInterface::STATUS_IN_STOCK,
    ];

    /**
     * @param string $field
     * @param array $expectedErrorData
     * @throws \Exception
     * @magentoApiDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/products.php
     * @magentoApiDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/sources.php
     * @dataProvider dataProviderRequiredFields
     */
    public function testCreateWithMissedRequiredFields(string $field, array $expectedErrorData)
    {
        $data = $this->validData;
        unset($data[$field]);

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => Request::HTTP_METHOD_POST,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'operation' => self::SERVICE_NAME . 'Execute',
            ],
        ];
        $this->webApiCall($serviceInfo, $data, $expectedErrorData);
    }

    /**
     * @return array
     */
    public function dataProviderRequiredFields(): array
    {
        return [
            'without_' . SourceItemInterface::SKU => [
                SourceItemInterface::SKU,
                [
                    'message' => 'Validation Failed',
                    'errors' => [
                        [
                            'message' => '"%field" can not be empty.',
                            'parameters' => [
                                'field' => SourceItemInterface::SKU,
                            ],
                        ],
                    ],
                ],
            ],
            'without_' . SourceItemInterface::SOURCE_ID => [
                SourceItemInterface::SOURCE_ID,
                [
                    'message' => 'Validation Failed',
                    'errors' => [
                        [
                            'message' => '"%field" should be numeric.',
                            'parameters' => [
                                'field' => SourceItemInterface::SOURCE_ID,
                            ],
                        ],
                    ],
                ],
            ],
            'without_' . SourceItemInterface::QUANTITY => [
                SourceItemInterface::QUANTITY,
                [
                    'message' => 'Validation Failed',
                    'errors' => [
                        [
                            'message' => '"%field" should be numeric.',
                            'parameters' => [
                                'field' => SourceItemInterface::QUANTITY,
                            ],
                        ],
                    ],
                ],
            ],
            'without_' . SourceItemInterface::STATUS => [
                SourceItemInterface::STATUS,
                [
                    'message' => 'Validation Failed',
                    'errors' => [
                        [
                            'message' => '"%field" should a known status.',
                            'parameters' => [
                                'field' => SourceItemInterface::STATUS,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $field
     * @param string|null $value
     * @param array $expectedErrorData
     * @magentoApiDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/products.php
     * @magentoApiDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/sources.php
     * @dataProvider failedValidationDataProvider
     */
    public function testFailedValidationOnCreate(string $field, $value, array $expectedErrorData)
    {
        $data = $this->validData;
        $data[$field] = $value;

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => Request::HTTP_METHOD_POST,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'operation' => self::SERVICE_NAME . 'Execute',
            ],
        ];
        $this->webApiCall($serviceInfo, $data, $expectedErrorData);
    }

    /**
     * @return array
     */
    public function failedValidationDataProvider(): array
    {
        return [
            'empty_' . SourceItemInterface::SKU => [
                SourceItemInterface::SKU,
                null,
                [
                    'message' => 'Validation Failed',
                    'errors' => [
                        [
                            'message' => '"%field" can not be empty.',
                            'parameters' => [
                                'field' => SourceItemInterface::SKU,
                            ],
                        ],
                    ],
                ],
            ],
            'empty_' . SourceItemInterface::SKU => [
                SourceItemInterface::SKU,
                '',
                [
                    'message' => 'Validation Failed',
                    'errors' => [
                        [
                            'message' => '"%field" can not be empty.',
                            'parameters' => [
                                'field' => SourceItemInterface::SKU,
                            ],
                        ],
                    ],
                ],
            ],
            'whitespaces_' . SourceItemInterface::SKU => [
                SourceItemInterface::SKU,
                ' ',
                [
                    'message' => 'Validation Failed',
                    'errors' => [
                        [
                            'message' => '"%field" can not be empty.',
                            'parameters' => [
                                'field' => SourceItemInterface::SKU,
                            ],
                        ],
                    ],
                ],
            ],
            'unknown_' . SourceItemInterface::STATUS => [
                SourceItemInterface::STATUS,
                '999999',
                [
                    'message' => 'Validation Failed',
                    'errors' => [
                        [
                            'message' => '"%field" should a known status.',
                            'parameters' => [
                                'field' => SourceItemInterface::STATUS,
                            ],
                        ],
                    ],
                ],
            ],
            'null_' . SourceItemInterface::QUANTITY => [
                SourceItemInterface::QUANTITY,
                NULL,
                [
                    'message' => 'Validation Failed',
                    'errors' => [
                        [
                            'message' => '"%field" should be numeric.',
                            'parameters' => [
                                'field' => SourceItemInterface::QUANTITY,
                            ],
                        ],
                    ],
                ],
            ],
            'empty_' . SourceItemInterface::QUANTITY => [
                SourceItemInterface::QUANTITY,
                '',
                [
                    'message' => 'Validation Failed',
                    'errors' => [
                        [
                            'message' => '"%field" should be numeric.',
                            'parameters' => [
                                'field' => SourceItemInterface::QUANTITY,
                            ],
                        ],
                    ],
                ],
            ],
            'string_' . SourceItemInterface::QUANTITY => [
                SourceItemInterface::QUANTITY,
                'test',
                [
                    'message' => 'Validation Failed',
                    'errors' => [
                        [
                            'message' => '"%field" should be numeric.',
                            'parameters' => [
                                'field' => SourceItemInterface::QUANTITY,
                            ],
                        ],
                    ],
                ],
            ],
            'null_' . SourceItemInterface::SOURCE_ID => [
                SourceItemInterface::SOURCE_ID,
                NULL,
                [
                    'message' => 'Validation Failed',
                    'errors' => [
                        [
                            'message' => '"%field" should be numeric.',
                            'parameters' => [
                                'field' => SourceItemInterface::SOURCE_ID,
                            ],
                        ],
                    ],
                ],
            ],
            'empty_' . SourceItemInterface::SOURCE_ID => [
                SourceItemInterface::SOURCE_ID,
                '',
                [
                    'message' => 'Validation Failed',
                    'errors' => [
                        [
                            'message' => '"%field" should be numeric.',
                            'parameters' => [
                                'field' => SourceItemInterface::SOURCE_ID,
                            ],
                        ],
                    ],
                ],
            ],
            'array_' . SourceItemInterface::SOURCE_ID => [
                SourceItemInterface::SOURCE_ID,
                [],
                [
                    'message' => 'Validation Failed',
                    'errors' => [
                        [
                            'message' => '"%field" should be numeric.',
                            'parameters' => [
                                'field' => SourceItemInterface::SOURCE_ID,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array $serviceInfo
     * @param array $data
     * @param array $expectedErrorData
     * @return void
     * @throws \Exception
     */
    private function webApiCall(array $serviceInfo, array $data, array $expectedErrorData)
    {
        try {
            $this->_webApiCall($serviceInfo, ['source' => $data]);
            $this->fail('Expected throwing exception');
        } catch (\Exception $exception) {
            if (TESTS_WEB_API_ADAPTER === self::ADAPTER_REST) {
                self::assertEquals($expectedErrorData, $this->processRestExceptionResult($exception));
                self::assertEquals(Exception::HTTP_BAD_REQUEST, $exception->getCode());
            } elseif (TESTS_WEB_API_ADAPTER === self::ADAPTER_SOAP) {
                $this->assertInstanceOf('SoapFault', $exception);
                $expectedWrappedErrors = [];
                foreach ($expectedErrorData['errors'] as $error) {
                    // @see \Magento\TestFramework\TestCase\WebapiAbstract::getActualWrappedErrors()
                    $expectedWrappedErrors[] = [
                        'message' => $error['message'],
                        'params' => $error['parameters'],
                    ];
                }
                $this->checkSoapFault($exception, $expectedErrorData['message'], 'env:Sender', [], $expectedWrappedErrors);
            } else {
                throw $exception;
            }
        }
    }
}
