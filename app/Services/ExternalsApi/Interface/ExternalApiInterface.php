<?php

namespace App\Services\ExternalsApi\Interface;

interface ExternalApiInterface
{
    public function findGameInApi(string $slug): array;
    public function sortNeededData(array $data): array;
}
