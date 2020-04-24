<?php

namespace Bitrix24ApiWrapper\Request\CRM\Lead;

use Bitrix24ApiWrapper\Request;
use Bitrix24ApiWrapper\Entity;

class Items extends Request\AbstractItems {

    public function apiMethod(): string {
        return 'crm.lead.list';
    }

    public function responseEntity(): ?string {
        return Entity\CRM\Lead::class;
    }

    protected function _defaultSelects(): array {
        return [Entity\CRM\Lead::PROPERTY_EMAIL, Entity\CRM\Lead::PROPERTY_PHONE];
    }
}