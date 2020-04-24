<?php

namespace Bitrix24ApiWrapper\Test\Engine;

use Bitrix24ApiWrapper\Engine;
use Bitrix24ApiWrapper\Request;
use Bitrix24ApiWrapper\Library;
use Bitrix24ApiWrapper\Entity;

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
            $this->_prepareMockResponse(__DIR__ . '/Response/crm_lead_list.json')
        );
        $actualResponse = $this->_engine()->execute(Request\Custom::get('crm.lead.list'));
        $this->assertEquals([
            [
                'ID' => '4',
                'TITLE' => 'Новый лид от DIO',
                'HONORIFIC' => null,
                'NAME' => 'DIO',
                'SECOND_NAME' => null,
                'LAST_NAME' => 'BRANDO',
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
                'COMMENTS' => "email: test@gmail.xcom<br>\nname: DIO<br>\nphone: 81111111111<br>\nTextarea: Запись на 10или 21января на тридинг<br>\nformname: Записаться на обучение",
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
                        'VALUE' => 'test@gmail.xcom',
                        'TYPE_ID' => 'EMAIL',
                    ],
                ],
                'PHONE' => [
                    [
                        'ID' => '4',
                        'VALUE_TYPE' => 'WORK',
                        'VALUE' => '81111111111',
                        'TYPE_ID' => 'PHONE',
                    ],
                ],
            ],
        ], $actualResponse);
    }

    public function testSendItemsRequest(): void {
        $this->_engine()->setMockResponse(
            $this->_prepareMockGetRequest('crm.lead.list'),
            $this->_prepareMockResponse(__DIR__ . '/Response/crm_lead_list.json')
        );
        /** @var Entity\CRM\Lead[] $actualResponse */
        $actualResponse = $this->_engine()->execute(new Request\CRM\Lead\Items());
        $this->assertFalse($actualResponse[0]->unfamiliarParameter('UF_CRM_1572598226'));
        $this->assertEquals([$this->_expectedLead()], $actualResponse);
    }

    private function _expectedLead(): Entity\CRM\Lead {
        $phone = new Entity\CRM\ContactData();
        $phone->ID = '4';
        $phone->VALUE_TYPE = 'WORK';
        $phone->VALUE = '81111111111';
        $phone->TYPE_ID = 'PHONE';

        $email = new Entity\CRM\ContactData();
        $email->ID = '2';
        $email->VALUE_TYPE = 'WORK';
        $email->VALUE = 'test@gmail.xcom';
        $email->TYPE_ID = 'EMAIL';

        $lead = new Entity\CRM\Lead();
        $lead->ID = '4';
        $lead->TITLE = 'Новый лид от DIO';
        $lead->NAME = 'DIO';
        $lead->LAST_NAME = 'BRANDO';
        $lead->IS_RETURN_CUSTOMER = 'N';
        $lead->BIRTHDATE = '';
        $lead->STATUS_ID = 'NEW';
        $lead->COMMENTS = "email: test@gmail.xcom<br>\nname: DIO<br>\nphone: 81111111111<br>\nTextarea: Запись на 10или 21января на тридинг<br>\nformname: Записаться на обучение";
        $lead->CURRENCY_ID = 'RUB';
        $lead->OPPORTUNITY = '0.00';
        $lead->HAS_PHONE = 'Y';
        $lead->HAS_EMAIL = 'Y';
        $lead->HAS_IMOL = 'N';
        $lead->ASSIGNED_BY_ID = '1';
        $lead->CREATED_BY_ID = '1';
        $lead->MODIFY_BY_ID = '1';
        $lead->DATE_CREATE = '2018-12-29T22:00:41+03:00';
        $lead->DATE_MODIFY = '2018-12-29T22:00:41+03:00';
        $lead->DATE_CLOSED = '';
        $lead->STATUS_SEMANTIC_ID = 'P';
        $lead->OPENED = 'N';
        $lead->EMAIL = [$email];
        $lead->PHONE = [$phone];
        $lead->UF_CRM_1572597853 = false;
        $lead->UF_CRM_1572597891 = false;
        $lead->UF_CRM_1572597944 = false;
        $lead->UF_CRM_1572598005 = '';
        $lead->UF_CRM_1572598029 = '';
        $lead->UF_CRM_1572598048 = '';
        $lead->UF_CRM_1572598083 = '';
        $lead->UF_CRM_1572598126 = false;
        $lead->UF_CRM_1572598145 = false;
        $lead->UF_CRM_1572598226 = false;
        $lead->UF_CRM_1572598270 = false;
        $lead->UF_CRM_1572598420 = false;
        $lead->UF_CRM_1572598454 = [];
        $lead->UF_CRM_1572598529 = false;
        return $lead;
    }

    protected function _prepareUrl(string $apiMethod): string {
        return self::WEB_HOOK . "/{$apiMethod}";
    }

    protected function _initEngine(): Mock\Extension\MockedEngineInterface {
        return new Mock\WebHook(self::WEB_HOOK);
    }
}