<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication;

use Spryker\Shared\GuiTable\Configuration\Expander\ConfigurationDefaultValuesExpander;
use Spryker\Shared\GuiTable\Configuration\Expander\ConfigurationDefaultValuesExpanderInterface;
use Spryker\Shared\GuiTable\Configuration\Translator\ConfigurationTranslatorInterface;
use Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface;
use Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilEncodingServiceInterface;
use Spryker\Shared\GuiTable\GuiTableFactory;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\DataRequest\DataRequestBuilder;
use Spryker\Shared\GuiTable\Http\DataRequest\DataRequestBuilderInterface;
use Spryker\Shared\GuiTable\Http\DataResponse\DataResponseFormatter;
use Spryker\Shared\GuiTable\Http\DataResponse\DataResponseFormatterInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutor;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Shared\GuiTable\Http\HttpJsonResponseBuilder;
use Spryker\Shared\GuiTable\Http\HttpResponseBuilderInterface;
use Spryker\Shared\GuiTable\Normalizer\DateRangeRequestFilterValueNormalizer;
use Spryker\Shared\GuiTable\Normalizer\DateRangeRequestFilterValueNormalizerInterface;
use Spryker\Shared\Twig\TwigFunctionProvider;
use Spryker\Zed\GuiTable\Communication\Translator\ConfigurationTranslator;
use Spryker\Zed\GuiTable\Communication\Twig\GuiTableConfigurationFunctionProvider;
use Spryker\Zed\GuiTable\Dependency\Facade\GuiTableToTranslatorFacadeInterface;
use Spryker\Zed\GuiTable\GuiTableDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\GuiTable\GuiTableConfig getConfig()
 */
class GuiTableCommunicationFactory extends AbstractCommunicationFactory
{
    public function createGuiTableConfigurationFunctionProvider(): TwigFunctionProvider
    {
        return new GuiTableConfigurationFunctionProvider(
            $this->getUtilEncodingService(),
            $this->createConfigurationDefaultValuesExpander(),
            $this->createConfigurationTranslator(),
        );
    }

    public function createConfigurationTranslator(): ConfigurationTranslatorInterface
    {
        return new ConfigurationTranslator($this->getTranslatorFacade());
    }

    public function createConfigurationDefaultValuesExpander(): ConfigurationDefaultValuesExpanderInterface
    {
        return new ConfigurationDefaultValuesExpander($this->getConfig());
    }

    public function createGuiTableFactory(): GuiTableFactoryInterface
    {
        return new GuiTableFactory();
    }

    public function createDateRangeRequestFilterValueNormalizer(): DateRangeRequestFilterValueNormalizerInterface
    {
        return new DateRangeRequestFilterValueNormalizer();
    }

    public function createDataRequestBuilder(): DataRequestBuilderInterface
    {
        return new DataRequestBuilder(
            $this->getUtilEncodingService(),
            $this->getConfig(),
            $this->createDateRangeRequestFilterValueNormalizer(),
        );
    }

    public function createDataResponseFormatter(): DataResponseFormatterInterface
    {
        return new DataResponseFormatter(
            $this->getUtilDateTimeService(),
            $this->getConfig(),
        );
    }

    public function createHttpJsonResponseBuilder(): HttpResponseBuilderInterface
    {
        return new HttpJsonResponseBuilder();
    }

    public function createGuiTableDataRequestExecutor(): GuiTableDataRequestExecutorInterface
    {
        return new GuiTableDataRequestExecutor(
            $this->createDataRequestBuilder(),
            $this->createDataResponseFormatter(),
            $this->createHttpJsonResponseBuilder(),
        );
    }

    public function getUtilEncodingService(): GuiTableToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(GuiTableDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    public function getUtilDateTimeService(): GuiTableToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(GuiTableDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    public function getTranslatorFacade(): GuiTableToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(GuiTableDependencyProvider::FACADE_TRANSLATOR);
    }
}
