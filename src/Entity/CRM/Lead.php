<?php

namespace Bitrix24ApiWrapper\Entity\CRM;

use Bitrix24ApiWrapper\Entity;

class Lead extends Entity\AbstractBasic {

    public const PROPERTY_PHONE = 'PHONE';
    public const PROPERTY_EMAIL = 'EMAIL';

    /** @var string */
    public $TITLE;

    /** @var string */
    public $HONORIFIC;

    /** @var string */
    public $NAME;

    /** @var string */
    public $SECOND_NAME;

    /** @var string */
    public $LAST_NAME;

    /** @var string */
    public $EMAIL;

    /** @var string */
    public $PHONE;

    /** @var string */
    public $COMPANY_TITLE;

    /** @var string */
    public $COMPANY_ID;

    /** @var string */
    public $CONTACT_ID;

    /** @var string */
    public $IS_RETURN_CUSTOMER;

    /** @var string */
    public $BIRTHDATE;

    /** @var string */
    public $SOURCE_ID;

    /** @var string */
    public $SOURCE_DESCRIPTION;

    /** @var string */
    public $STATUS_ID;

    /** @var string */
    public $STATUS_DESCRIPTION;

    /** @var string */
    public $POST;

    /** @var string */
    public $COMMENTS;

    /** @var string */
    public $CURRENCY_ID;

    /** @var string */
    public $OPPORTUNITY;

    /** @var string */
    public $HAS_PHONE;

    /** @var string */
    public $HAS_EMAIL;

    /** @var string */
    public $HAS_IMOL;

    /** @var string */
    public $ASSIGNED_BY_ID;

    /** @var string */
    public $CREATED_BY_ID;

    /** @var string */
    public $MODIFY_BY_ID;

    /** @var string */
    public $DATE_CREATE;

    /** @var string */
    public $DATE_MODIFY;

    /** @var string */
    public $DATE_CLOSED;

    /** @var string */
    public $STATUS_SEMANTIC_ID;

    /** @var string */
    public $OPENED;

    /** @var string */
    public $ORIGINATOR_ID;

    /** @var string */
    public $ORIGIN_ID;

    /** @var string */
    public $ADDRESS;

    /** @var string */
    public $ADDRESS_2;

    /** @var string */
    public $ADDRESS_CITY;

    /** @var string */
    public $ADDRESS_POSTAL_CODE;

    /** @var string */
    public $ADDRESS_REGION;

    /** @var string */
    public $ADDRESS_PROVINCE;

    /** @var string */
    public $ADDRESS_COUNTRY;

    /** @var string */
    public $ADDRESS_COUNTRY_CODE;

    /** @var string */
    public $UTM_SOURCE;

    /** @var string */
    public $UTM_MEDIUM;

    /** @var string */
    public $UTM_CAMPAIGN;

    /** @var string */
    public $UTM_CONTENT;

    /** @var string */
    public $UTM_TERM;

    public function propertyConfiguration(string $propertyName): ?Entity\PropertyConfiguration\BasicInterface {
        switch ($propertyName) {
            case self::PROPERTY_PHONE:
            case self::PROPERTY_EMAIL:
                return new Entity\PropertyConfiguration\Basic(ContactData::class, true);
            default:
                return null;
        }
    }
}