<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Configuration\Expander;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;

interface ConfigurationDefaultValuesExpanderInterface
{
    public function setDefaultValues(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer;
}
