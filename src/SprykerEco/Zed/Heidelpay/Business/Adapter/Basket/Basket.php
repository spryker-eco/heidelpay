<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\Heidelpay\Business\Adapter\Basket;

use Generated\Shared\Transfer\HeidelpayBasketRequestTransfer;
use Generated\Shared\Transfer\HeidelpayBasketResponseTransfer;
use Heidelpay\PhpBasketApi\Object\Authentication;
use Heidelpay\PhpBasketApi\Object\Basket as HeidelpayBasket;
use Heidelpay\PhpBasketApi\Request;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\BasketRequestToHeidelpayInterface;
use SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\BasketResponseFromHeidelpayInterface;
use SprykerEco\Zed\Heidelpay\HeidelpayConfigInterface;

class Basket implements BasketInterface
{
    /**
     * @var \SprykerEco\Zed\Heidelpay\HeidelpayConfigInterface
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\BasketResponseFromHeidelpayInterface
     */
    protected $responseMapper;

    /**
     * @var \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\BasketRequestToHeidelpayInterface
     */
    protected $requestMapper;

    /**
     * @param \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\BasketRequestToHeidelpayInterface $requestMapper
     * @param \SprykerEco\Zed\Heidelpay\Business\Adapter\Mapper\BasketResponseFromHeidelpayInterface $responseMapper
     * @param \SprykerEco\Zed\Heidelpay\HeidelpayConfigInterface $config
     */
    public function __construct(
        BasketRequestToHeidelpayInterface $requestMapper,
        BasketResponseFromHeidelpayInterface $responseMapper,
        HeidelpayConfigInterface $config
    ) {
        $this->requestMapper = $requestMapper;
        $this->responseMapper = $responseMapper;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\HeidelpayBasketRequestTransfer $heidelpayBasketRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HeidelpayBasketResponseTransfer
     */
    public function addNewBasket(HeidelpayBasketRequestTransfer $heidelpayBasketRequestTransfer): HeidelpayBasketResponseTransfer
    {
        $authentication = $this->doAuthentication();

        $heidelpayBasket = new HeidelpayBasket();
        $this->requestMapper->map($heidelpayBasketRequestTransfer, $heidelpayBasket);

        $request = new Request($authentication, $heidelpayBasket);
        $response = $request->addNewBasket();

        $heidelpayBasketResponseTransfer = new HeidelpayBasketResponseTransfer();
        $this->responseMapper->map($response, $heidelpayBasketResponseTransfer);

        return $heidelpayBasketResponseTransfer;
    }

    /**
     * @return \Heidelpay\PhpBasketApi\Object\Authentication
     */
    protected function doAuthentication(): Authentication
    {
        return new Authentication(
            $this->config->getMerchantUserLogin(),
            $this->config->getMerchantUserPassword(),
            $this->config->getMerchantSecuritySender()
        );
    }
}
