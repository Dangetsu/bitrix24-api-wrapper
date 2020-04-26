<?php

namespace Bitrix24ApiWrapper\Request\CRM\Lead;

use Bitrix24ApiWrapper\Request;

class Add extends Request\AbstractSave {

    public function apiMethod(): string {
        return 'crm.lead.add';
    }
}