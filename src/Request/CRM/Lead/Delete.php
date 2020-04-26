<?php

namespace Bitrix24ApiWrapper\Request\CRM\Lead;

use Bitrix24ApiWrapper\Request;

class Delete extends Request\AbstractDelete {

    public function apiMethod(): string {
        return 'crm.lead.delete';
    }
}