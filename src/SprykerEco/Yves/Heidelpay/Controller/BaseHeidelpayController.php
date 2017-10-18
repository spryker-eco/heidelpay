<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Heidelpay\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \SprykerEco\Yves\Heidelpay\HeidelpayFactory getFactory()
 */
class BaseHeidelpayController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getUrldecodedRequestBody(Request $request)
    {
        $allRequestParameters = $request->request->all();

        foreach ($allRequestParameters as $key => $value) {
            if (is_string($value)) {
                $allRequestParameters[$key] = urldecode($value);
            }
        }

        return $allRequestParameters;
    }

    /**
     * @return \SprykerEco\Yves\Heidelpay\HeidelpayConfigInterface
     */
    protected function getConfig()
    {
        return $this->getFactory()->getYvesConfig();
    }

    /**
     * @param callable|null $callback
     * @param int $status
     * @param array $headers
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function streamedResponse($callback = null, $status = 200, $headers = [])
    {
        $streamedResponse = new StreamedResponse($callback, $status, $headers);
        $streamedResponse->send();

        return $streamedResponse;
    }

}
