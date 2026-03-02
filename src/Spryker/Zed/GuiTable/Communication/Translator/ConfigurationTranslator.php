<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\Translator;

use Spryker\Shared\GuiTable\Configuration\Translator\AbstractConfigurationTranslator;
use Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToTranslatorFacadeInterface;

class ConfigurationTranslator extends AbstractConfigurationTranslator
{
    /**
     * @var \Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToTranslatorFacadeInterface
     */
    protected GuiTableToTranslatorFacadeInterface $translatorFacade;

    public function __construct(GuiTableToTranslatorFacadeInterface $translatorFacade)
    {
        $this->translatorFacade = $translatorFacade;
    }

    protected function translate(string $key): string
    {
        return $this->translatorFacade->trans($key);
    }
}
