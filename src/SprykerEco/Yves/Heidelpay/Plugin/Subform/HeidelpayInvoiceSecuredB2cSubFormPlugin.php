<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Plugin\Subform;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayFactory getFactory()
 */
class HeidelpayInvoiceSecuredB2cSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Creates sub form for InvoiceSecuredB2c payment method.
     *
     * @api
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createSubForm(): SubFormInterface
    {
        return $this->getFactory()->createInvoiceSecuredB2cSubForm();
    }

    /**
     * {@inheritDoc}
     *  - Creates data provider for InvoiceSecuredB2c payment method.
     *
     * @api
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return $this->getFactory()->createInvoiceSecuredB2cDataProvider();
    }
}
