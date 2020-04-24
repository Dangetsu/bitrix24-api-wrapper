<?php

namespace Bitrix24ApiWrapper\Request;

interface BasicInterface {

    public const METHOD_GET  = 'GET';
    public const METHOD_POST = 'POST';

    public function httpMethod(): string;

    public function apiMethod(): string;

    public function parameters(): array;

    public function responseEntity(): ?string;
}