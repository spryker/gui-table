<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Http\DataResponse;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\Configuration\GuiTableConfigInterface;
use Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface;

class DataResponseFormatter implements DataResponseFormatterInterface
{
    /**
     * @var string
     */
    protected const KEY_DATA_RESPONSE_ARRAY_DATA = 'data';

    /**
     * @var \Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface
     */
    protected GuiTableToUtilDateTimeServiceInterface $utilDateTimeService;

    /**
     * @var \Spryker\Shared\GuiTable\Configuration\GuiTableConfigInterface
     */
    protected GuiTableConfigInterface $guiTableConfig;

    /**
     * @param \Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface $utilDateTimeService
     * @param \Spryker\Shared\GuiTable\Configuration\GuiTableConfigInterface $guiTableConfig
     */
    public function __construct(GuiTableToUtilDateTimeServiceInterface $utilDateTimeService, GuiTableConfigInterface $guiTableConfig)
    {
        $this->utilDateTimeService = $utilDateTimeService;
        $this->guiTableConfig = $guiTableConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array<mixed>
     */
    public function formatGuiTableDataResponse(
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): array {
        $guiTableDataResponseArray = $guiTableDataResponseTransfer->toArray(true, true);
        $guiTableDataResponseArray[static::KEY_DATA_RESPONSE_ARRAY_DATA] = $this->formatValues(
            $guiTableDataResponseArray,
            $guiTableConfigurationTransfer,
        );

        unset($guiTableDataResponseArray[GuiTableDataResponseTransfer::ROWS]);

        return $guiTableDataResponseArray;
    }

    /**
     * @param array<mixed> $guiTableDataResponseArray
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function formatValues(
        array $guiTableDataResponseArray,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): array {
        $rows = array_map(function (array $rowData): array {
            return $rowData[GuiTableRowDataResponseTransfer::RESPONSE_DATA];
        }, $guiTableDataResponseArray[GuiTableDataResponseTransfer::ROWS]);

        $indexedColumnTypes = $this->getIndexedColumnTypesByColumnIds($guiTableConfigurationTransfer);

        $defaultTimezone = $this->guiTableConfig->getDefaultTimezone();
        foreach ($rows as $rowKey => $row) {
            foreach ($row as $idColumn => $value) {
                if (isset($indexedColumnTypes[$idColumn]) && $indexedColumnTypes[$idColumn] === GuiTableConfigurationBuilderInterface::COLUMN_TYPE_DATE) {
                    $rows[$rowKey][$idColumn] = $value ? $this->utilDateTimeService->formatDateTimeToIso8601($value, $defaultTimezone) : null;
                }
            }
        }

        return $rows;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return array<string>
     */
    protected function getIndexedColumnTypesByColumnIds(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $indexedColumnTypes = [];
        foreach ($guiTableConfigurationTransfer->getColumns() as $guiTableColumnConfigurationTransfer) {
            $indexedColumnTypes[$guiTableColumnConfigurationTransfer->getIdOrFail()] = $guiTableColumnConfigurationTransfer->getTypeOrFail();
        }

        return $indexedColumnTypes;
    }
}
