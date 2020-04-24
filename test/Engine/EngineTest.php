<?php

namespace Bitrix24ApiWrapper\Test\Engine;

use Bitrix24ApiWrapper\Engine;
use Bitrix24ApiWrapper\Request;
use Bitrix24ApiWrapper\Library;

class EngineTest extends AbstractTest {

    private const WEB_HOOK = 'https://b24-xxxxxxx.bitrix24.ru/rest/1/******';

    /**
     * @param string $responseFile
     * @param int $responseCode
     * @param string $exceptionClass
     * @dataProvider errorsDataProvider
     */
    public function testErrors(string $responseFile, int $responseCode, string $exceptionClass): void {
        $this->_engine()->setMockResponse(
            $this->_prepareMockGetRequest('test'),
            $this->_prepareMockResponse($responseFile, $responseCode)
        );
        $this->expectException($exceptionClass);
        $this->_engine()->execute(Request\Custom::get('test'));
    }

    public function errorsDataProvider(): array {
        return [
            // response file                                         response code                  expected exception class
            [__DIR__ . '/Response/error_not_found_method.json',      self::HTTP_CODE_NOT_FOUND,     Engine\Exception\MethodNotFound::class],
            [__DIR__ . '/Response/error_deleted_portal.json',        self::HTTP_CODE_ACCESS_DENIED, Engine\Exception\PortalDeleted::class],
            [__DIR__ . '/Response/error_invalid_credentials.json',   self::HTTP_CODE_UNAUTHORIZED,  Engine\Exception\InvalidCredentials::class],
            [__DIR__ . '/Response/error_invalid_json.json',          self::HTTP_CODE_SUCCESS,       Library\Exception\JSON::class],
        ];
    }

    public function testSendGetRequest(): void {
        $this->_engine()->setMockResponse(
            $this->_prepareMockGetRequest('crm.lead.list'),
            $this->_prepareMockResponse(__DIR__ . '/Response/crm_lead_list.json', self::HTTP_CODE_SUCCESS)
        );
        $actualResponse = $this->_engine()->execute(Request\Custom::get('crm.lead.list'));
        $this->assertEquals([
            [
                'ID' => '2',
                'TITLE' => 'тестирование',
                'HONORIFIC' => null,
                'NAME' => null,
                'SECOND_NAME' => null,
                'LAST_NAME' => null,
                'COMPANY_TITLE' => null,
                'COMPANY_ID' => '2',
                'CONTACT_ID' => '2',
                'IS_RETURN_CUSTOMER' => 'N',
                'BIRTHDATE' => '',
                'SOURCE_ID' => 'CALL',
                'SOURCE_DESCRIPTION' => null,
                'STATUS_ID' => 'CONVERTED',
                'STATUS_DESCRIPTION' => null,
                'POST' => null,
                'COMMENTS' => null,
                'CURRENCY_ID' => 'RUB',
                'OPPORTUNITY' => '0.00',
                'HAS_PHONE' => 'N',
                'HAS_EMAIL' => 'N',
                'HAS_IMOL' => 'N',
                'ASSIGNED_BY_ID' => '1',
                'CREATED_BY_ID' => '1',
                'MODIFY_BY_ID' => '1',
                'DATE_CREATE' => '2018-12-28T14:18:14+03:00',
                'DATE_MODIFY' => '2018-12-28T15:03:34+03:00',
                'DATE_CLOSED' => '2018-12-28T15:03:34+03:00',
                'STATUS_SEMANTIC_ID' => 'S',
                'OPENED' => 'Y',
                'ORIGINATOR_ID' => null,
                'ORIGIN_ID' => null,
                'ADDRESS' => null,
                'ADDRESS_2' => null,
                'ADDRESS_CITY' => null,
                'ADDRESS_POSTAL_CODE' => null,
                'ADDRESS_REGION' => null,
                'ADDRESS_PROVINCE' => null,
                'ADDRESS_COUNTRY' => null,
                'ADDRESS_COUNTRY_CODE' => null,
                'UTM_SOURCE' => null,
                'UTM_MEDIUM' => null,
                'UTM_CAMPAIGN' => null,
                'UTM_CONTENT' => null,
                'UTM_TERM' => null,
            ],
        ], $actualResponse);
    }

    protected function _prepareUrl(string $apiMethod): string {
        return self::WEB_HOOK . "/{$apiMethod}";
    }

    protected function _initEngine(): Mock\Extension\MockedEngineInterface {
        return new Mock\WebHook(self::WEB_HOOK);
    }
}