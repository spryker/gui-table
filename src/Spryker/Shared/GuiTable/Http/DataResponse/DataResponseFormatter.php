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
use Spryker\Shared\GuiTable\Formatter\DateResponseColumnValueFormatterInterface;

class DataResponseFormatter implements DataResponseFormatterInterface
{
    protected const KEY_DATA_RESPONSE_ARRAY_DATA = 'data';

    /**
     * @var \Spryker\Shared\GuiTable\Formatter\DateResponseColumnValueFormatterInterface
     */
    protected $dateResponseColumnValueFormatter;

    /**
     * @param \Spryker\Shared\GuiTable\Formatter\DateResponseColumnValueFormatterInterface $dateResponseColumnValueFormatter
     */
    public function __construct(DateResponseColumnValueFormatterInterface $dateResponseColumnValueFormatter)
    {
        $this->dateResponseColumnValueFormatter = $dateResponseColumnValueFormatter;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return mixed[]
     */
    public function formatGuiTableDataResponse(
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): array {
        $guiTableDataResponseArray = $guiTableDataResponseTransfer->toArray(true, true);

        $guiTableDataResponseArray[static::KEY_DATA_RESPONSE_ARRAY_DATA] = array_map(function (array $rowData): array {
            return $rowData[GuiTableRowDataResponseTransfer::RESPONSE_DATA];
        }, $guiTableDataResponseArray[GuiTableDataResponseTransfer::ROWS]);
        unset($guiTableDataResponseArray[GuiTableDataResponseTransfer::ROWS]);

        $guiTableDataResponseArray = $this->executeResponseColumnValueFormatters(
            $guiTableDataResponseArray,
            $guiTableConfigurationTransfer
        );

        return $guiTableDataResponseArray;
    }

    /**
     * @param array $guiTableDataResponseArray
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return mixed[]
     */
    protected function executeResponseColumnValueFormatters(
        array $guiTableDataResponseArray,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): array {
        $indexedColumnTypes = $this->getIndexedColumnTypesByColumnIds($guiTableConfigurationTransfer);
        $guiTableData = $guiTableDataResponseArray[static::KEY_DATA_RESPONSE_ARRAY_DATA];

        foreach ($guiTableData as $tableRowKey => $tableRowData) {
            foreach ($tableRowData as $columnId => $columnValue) {
                if (isset($indexedColumnTypes[$columnId]) && $indexedColumnTypes[$columnId] === GuiTableConfigurationBuilderInterface::COLUMN_TYPE_DATE) {
                    $guiTableData[$tableRowKey][$columnId] = $this->dateResponseColumnValueFormatter->formatColumnValue($columnValue);
                }
            }
        }

        $guiTableDataResponseArray[static::KEY_DATA_RESPONSE_ARRAY_DATA] = $guiTableData;

        return $guiTableDataResponseArray;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return string[]
     */
    protected function getIndexedColumnTypesByColumnIds(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): array
    {
        $indexedColumnTypes = [];
        foreach ($guiTableConfigurationTransfer->getColumns() as $guiTableColumnConfigurationTransfer) {
            $indexedColumnTypes[$guiTableColumnConfigurationTransfer->getId()] = $guiTableColumnConfigurationTransfer->getType();
        }

        return $indexedColumnTypes;
    }
}
