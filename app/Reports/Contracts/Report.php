<?php

declare(strict_types=1);

namespace VOSTPT\Reports\Contracts;

use Generator;
use Psr\Log\LoggerInterface;

interface Report
{
    /**
     * Get the report file name.
     *
     * @param string $signature
     * @param bool   $zip
     *
     * @return string
     */
    public static function getFileName(string $signature, bool $zip = false): string;

    /**
     * Get the report header.
     *
     * @return array
     */
    public function getHeader(): array;

    /**
     * Get the report data.
     *
     * @return Generator
     */
    public function getData(): Generator;

    /**
     * Generate the report.
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function generate(LoggerInterface $logger): bool;

    /**
     * Get the report signature.
     *
     * @return string
     */
    public function getSignature(): string;

    /**
     * Is the report ready for download?
     *
     * @return bool
     */
    public function isReadyForDownload(): bool;
}
