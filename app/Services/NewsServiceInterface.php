<?php

namespace App\Services;

interface NewsServiceInterface
{
    public function getTopHeadline();
    public function getEverything($query, $page, $pageSize);
}