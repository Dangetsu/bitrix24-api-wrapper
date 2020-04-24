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
                'ID' => '4',
                'TITLE' => 'Новый лид от Tilda',
                'HONORIFIC' => null,
                'NAME' => 'Ирина',
                'SECOND_NAME' => null,
                'LAST_NAME' => null,
                'COMPANY_TITLE' => null,
                'COMPANY_ID' => null,
                'CONTACT_ID' => null,
                'IS_RETURN_CUSTOMER' => 'N',
                'BIRTHDATE' => '',
                'SOURCE_ID' => null,
                'SOURCE_DESCRIPTION' => null,
                'STATUS_ID' => 'NEW',
                'STATUS_DESCRIPTION' => null,
                'POST' => null,
                'COMMENTS' => "email: test@gmail.xcom<br>\nname: Ирина<br>\nphone: 81111111111<br>\nTextarea: Запись на 10или 21января на тридинг<br>\nformname: Записаться на обучение",
                'CURRENCY_ID' => 'RUB',
                'OPPORTUNITY' => '0.00',
                'HAS_PHONE' => 'Y',
                'HAS_EMAIL' => 'Y',
                'HAS_IMOL' => 'N',
                'ASSIGNED_BY_ID' => '1',
                'CREATED_BY_ID' => '1',
                'MODIFY_BY_ID' => '1',
                'DATE_CREATE' => '2018-12-29T22:00:41+03:00',
                'DATE_MODIFY' => '2018-12-29T22:00:41+03:00',
                'DATE_CLOSED' => '',
                'STATUS_SEMANTIC_ID' => 'P',
                'OPENED' => 'N',
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
                'UF_CRM_1553184173848' => null,
                'UF_CRM_1553844257589' => null,
                'UF_CRM_1553844301381' => null,
                'UF_CRM_1556115738538' => null,
                'UF_CRM_1556115755078' => null,
                'UF_CRM_1572597853' => false,
                'UF_CRM_1572597891' => false,
                'UF_CRM_1572597915' => null,
                'UF_CRM_1572597944' => false,
                'UF_CRM_1572597962' => null,
                'UF_CRM_1572597984' => null,
                'UF_CRM_1572598005' => '',
                'UF_CRM_1572598029' => '',
                'UF_CRM_1572598048' => '',
                'UF_CRM_1572598083' => '',
                'UF_CRM_1572598107' => null,
                'UF_CRM_1572598126' => false,
                'UF_CRM_1572598145' => false,
                'UF_CRM_1572598182' => null,
                'UF_CRM_1572598204' => null,
                'UF_CRM_1572598226' => false,
                'UF_CRM_1572598270' => false,
                'UF_CRM_1572598352' => null,
                'UF_CRM_1572598420' => false,
                'UF_CRM_1572598437' => null,
                'UF_CRM_1572598454' => [

                ],
                'UF_CRM_1572598490' => null,
                'UF_CRM_1572598529' => false,
                'UF_CRM_1572598558' => null,
                'UF_CRM_1579076012' => null,
                'EMAIL' => [
                    [
                        'ID' => '2',
                        'VALUE_TYPE' => 'WORK',
                        'VALUE' => 'xana4500@mail.ru',
                        'TYPE_ID' => 'EMAIL',
                    ],
                ],
                'PHONE' => [
                    [
                        'ID' => '4',
                        'VALUE_TYPE' => 'WORK',
                        'VALUE' => '89265374378',
                        'TYPE_ID' => 'PHONE',
                    ],
                ],
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