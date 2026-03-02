<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Configuration;

interface GuiTableConfigInterface
{
    public function getDefaultDataSourceType(): string;

    /**
     * @return array<string>
     */
    public function getDefaultEnabledFeatures(): array;

    /**
     * @return array<int>
     */
    public function getDefaultAvailablePageSizes(): array;

    public function getDefaultPageSize(): int;

    public function getDefaultSearchPlaceholder(): string;

    public function getDefaultTimezone(): ?string;
}
