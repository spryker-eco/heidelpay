<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Checker;

interface SalesOrderCheckerInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function isCaptureApproved(int $idSalesOrder): bool;

    /**
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function isRefunded(int $idSalesOrder): bool;

    /**
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function isDebitOnRegistrationCompleted(int $idSalesOrder): bool;
}
