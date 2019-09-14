<?php

namespace app\Services;

interface OceanServiceInterface
{
    public function matchPatterns(array $patterns, string $subject): string;
}
