<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Heidelpay\Mapper;

use Generated\Shared\Transfer\HeidelpayIdealAuthorizeFormTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;

class HeidelpayResponseToIdealAuthorizeForm implements HeidelpayResponseToIdealAuthorizeFormInterface
{
    /**
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\HeidelpayIdealAuthorizeFormTransfer $idealAuthoriseFormTransfer
     *
     * @return void
     */
    public function map(HeidelpayResponseTransfer $responseTransfer, HeidelpayIdealAuthorizeFormTransfer $idealAuthoriseFormTransfer): void
    {
        $idealAuthoriseFormTransfer
            ->setActionUrl(
                $responseTransfer->getPaymentFormUrl(),
            )
            ->setCountries(
                $responseTransfer->getConfig()->getBankCountries(),
            )
            ->setBanks(
                $responseTransfer->getConfig()->getBankBrands(),
            );
    }
}
