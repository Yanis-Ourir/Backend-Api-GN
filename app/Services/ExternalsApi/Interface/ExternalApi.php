<?php

namespace App\Services\ExternalsApi\Interface;

interface ExternalApi
{
    public function findGameInApi(string $slug): array;
    public function sortNeededData(array $data): array;
}
