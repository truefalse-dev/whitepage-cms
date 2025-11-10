<?php

namespace WhitePage\Contracts;

interface ServiceInterface
{
    public function makeRequest($request);
    public function pathRouter($request);
}
