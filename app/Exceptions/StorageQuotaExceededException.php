<?php

namespace App\Exceptions;

use Exception;

class StorageQuotaExceededException extends Exception
{
    public function __construct(
        public readonly string $tier,
        public readonly int $used,
        public readonly int $limit,
        public readonly int $attempted,
    ) {
        parent::__construct('Storage quota exceeded.');
    }

    /**
     * @return array<string, int|string>
     */
    public function toArray(): array
    {
        return [
            'tier'      => $this->tier,
            'used'      => $this->used,
            'limit'     => $this->limit,
            'attempted' => $this->attempted,
        ];
    }
}
