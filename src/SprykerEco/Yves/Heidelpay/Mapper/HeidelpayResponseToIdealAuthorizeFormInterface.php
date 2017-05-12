<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Mapper;

use Generated\Shared\Transfer\HeidelpayIdealAuthorizeFormTransfer;
use Generated\Shared\Transfer\HeidelpayResponseTransfer;

interface HeidelpayResponseToIdealAuthorizeFormInterface
{

    /**
     * @param \Generated\Shared\Transfer\HeidelpayResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\HeidelpayIdealAuthorizeFormTransfer $idealAuthoriseFormTransfer
     *
     * @return void
     */
    public function map(HeidelpayResponseTransfer $responseTransfer, HeidelpayIdealAuthorizeFormTransfer $idealAuthoriseFormTransfer);

}
