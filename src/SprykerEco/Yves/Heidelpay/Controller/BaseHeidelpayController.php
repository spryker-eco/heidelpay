<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
    protected function getUrldecodedRequestBody(Request $request): array
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
     * @param callable|null $callback
     * @param int $status
     * @param array $headers
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function streamedResponse($callback = null, $status = 200, $headers = []): StreamedResponse
    {
        $streamedResponse = new StreamedResponse($callback, $status, $headers);
        $streamedResponse->send();

        return $streamedResponse;
    }
}
