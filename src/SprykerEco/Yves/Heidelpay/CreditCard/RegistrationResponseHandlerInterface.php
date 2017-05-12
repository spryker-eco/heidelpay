<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\CreditCard;

interface RegistrationResponseHandlerInterface
{

    /**
     * @param array $responseArray
     *
     * @return \Generated\Shared\Transfer\HeidelpayRegistrationResponseTransfer
     */
    public function handleRegistrationResponse(array $responseArray);

}
