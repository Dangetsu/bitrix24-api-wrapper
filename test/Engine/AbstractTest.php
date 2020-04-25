<?php

namespace Bitrix24ApiWrapper\Test\Engine;

use GuzzleHttp;
use GuzzleHttp\Psr7;
use Psr;

abstract class AbstractTest extends \PHPUnit\Framework\TestCase {

    protected const HTTP_CODE_SUCCESS       = 200;
    protected const HTTP_CODE_UNAUTHORIZED  = 401;
    protected const HTTP_CODE_ACCESS_DENIED = 403;
    protected const HTTP_CODE_NOT_FOUND     = 404;

    private const METHOD_GET  = 'GET';

    /** @var Mock\Extension\MockedEngineInterface */
    private $_engine;

    abstract protected function _prepareUrl(string $apiMethod): string;

    abstract protected function _initEngine(): Mock\Extension\MockedEngineInterface;

    protected function _engine(): Mock\Extension\MockedEngineInterface {
        if ($this->_engine === null) {
            $this->_engine = $this->_initEngine();
        }
        return $this->_engine;
    }

    protected function _prepareMockGetRequest(string $apiMethod, array $parameters = []): Psr\Http\Message\RequestInterface {
        $url = $this->_prepareUrl($apiMethod);
        $delimiter = mb_strpos($url, '?') !== false ? '&' : '?';
        $urlWithQuery = "{$url}{$delimiter}" . http_build_query($parameters);
        return new Psr7\Request(self::METHOD_GET, $urlWithQuery, [], null);
    }

    protected function _prepareMockResponse(string $responseFile, int $statusCode = 200): Psr\Http\Message\ResponseInterface {
        return new GuzzleHttp\Psr7\Response($statusCode, ['Content-Type' => 'application/json'], file_get_contents($responseFile));
    }

    // Just utils for generate code Entity initialization :)
    // Return:
    // $lead = new Entity\CRM\Lead();
    // $lead->ID = '4';
    // ...
    protected function _prepareEntityForUnitTest(string $variableName, string $entityClass, array $entityData): void {
        $preparedEntityClass = str_replace('Bitrix24ApiWrapper\\', '', $entityClass);
        $text = "\${$variableName} = new {$preparedEntityClass}();\n";
        foreach ($entityData as $key => $value) {
            if ($value === null) {
                continue;
            }
            $preparedValue = $this->_var_export54($value);
            $text .= "\${$variableName}->{$key} = {$preparedValue};\n";
        }
        die($text);
    }

    private function _var_export54($var, $indent="") {
        switch (gettype($var)) {
            case "string":
                return "'" . addcslashes($var, "\\\$\"\r\n\t\v\f") . "'";
            case "array":
                $indexed = array_keys($var) === range(0, count($var) - 1);
                $r = [];
                foreach ($var as $key => $value) {
                    $r[] = "$indent    "
                        . ($indexed ? "" : $this->_var_export54($key) . " => ")
                        . $this->_var_export54($value, "$indent    ");
                }

                $isNeedComma = count($r) > 0;
                return "[\n" . implode(",\n", $r) . ($isNeedComma ? ',' : '') . "\n" . $indent . "]";
            case "boolean":
                return $var ? "true" : "false";
            default:
                return $var === null ? 'null' : var_export($var, TRUE);
        }
    }
}