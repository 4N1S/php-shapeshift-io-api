<?php

declare(strict_types=1);

namespace Achse\ShapeShiftIo;

use Achse\ShapeShiftIo\ApiError\ApiErrorException;
use Achse\ShapeShiftIo\ApiError\NoPendingTransactionException;
use Achse\ShapeShiftIo\ApiError\NotDepositAddressException;
use Achse\ShapeShiftIo\ApiError\NoTransactionFoundException;
use Achse\ShapeShiftIo\ApiError\UnknownPairException;
use GuzzleHttp\Exception\RequestException;
use Nette\StaticClass;
use Nette\Utils\Json;
use Nette\Utils\Strings;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class ResultProcessor
{

    use StaticClass;

    /**
     * @param string $url
     * @param ResponseInterface $response
     * @return array|stdClass
     *
     * @throws ApiErrorException
     */
    public static function processResult(string $url, ResponseInterface $response)
    {
        $body = $response->getBody()->getContents();
        $body = Tools::jsonNumbersToString($body);
        $result = Json::decode($body);
        self::checkErrors($result, $url);

        return $result;
    }

    /**
     * @param array|stdClass $result
     * @param string $url
     * @throws ApiErrorException
     */
    private static function checkErrors($result, string $url)
    {
        $error = self::findErrorInResult($result);

        if ($error !== null) {
            if ($error === 'Unknown pair') {
                throw new UnknownPairException('Coin identifiers pair unknown.');

            } elseif ($error === 'This address is NOT a ShapeShift deposit address. Do not send anything to it.') {
                throw new NotDepositAddressException($error);

            } elseif ($error === 'Unable to find pending transaction') {
                throw new NoPendingTransactionException($error);

            } elseif ($error === 'No transaction found.') {
                throw new NoTransactionFoundException($error);

            } elseif (!self::isEndpointOkWithError($url)) {
                throw new ApiErrorException($error);
            }
        }
    }

    /**
     * @param stdClass|array $result
     * @return string|stdClass|null
     */
    private static function findErrorInResult($result)
    {
        $error = null;
        if ($result instanceof stdClass) {
            $error = $result->error ?? $result->err ?? null;
        }

        return $error;
    }

    /**
     * ShapeShift API does NOT provide 400 status code on error and for some endpoints
     * can be $result->error success response.
     *
     * @param string $url
     * @return bool
     */
    private static function isEndpointOkWithError(string $url): bool
    {
        return Strings::startsWith($url, Resources::VALIDATE_ADDRESS);
    }

    /**
     * @param RequestException $exception
     * @throws RequestFailedException
     */
    public static function handleGuzzleRequestException(RequestException $exception)
    {
        $message = sprintf('Request failed due: "%s".', $exception->getMessage());
        throw new RequestFailedException($message, $exception->getCode(), $exception);
    }

}
