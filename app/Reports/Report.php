<?php

declare(strict_types=1);

namespace VOSTPT\Reports;

use Box\Spout\Common\Exception\SpoutException;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;
use VOSTPT\Filters\Contracts\Filter;
use VOSTPT\Repositories\Contracts\Repository as ModelRepository;
use ZipArchive;

abstract class Report implements Contracts\Report
{
    /**
     * Model repository.
     *
     * @var \VOSTPT\Repositories\Contracts\Repository
     */
    protected $repository;

    /**
     * Filter instance.
     *
     * @var \VOSTPT\Filters\Filter
     */
    protected $filter;

    /**
     * Filesystem instance.
     *
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Report constructor.
     *
     * @param \VOSTPT\Repositories\Contracts\Repository   $repository
     * @param \VOSTPT\Filters\Contracts\Filter            $filter
     * @param \Illuminate\Contracts\Filesystem\Filesystem $filesystem
     */
    public function __construct(ModelRepository $repository, Filter $filter, Filesystem $filesystem)
    {
        $this->repository = $repository;
        $this->filter     = $filter;
        $this->filesystem = $filesystem;
    }

    /**
     * Get the report temporary file name.
     *
     * @return string
     */
    protected function getTemporaryFileName(): string
    {
        return \sprintf('%s.csv.tmp', $this->filter->getSignature(true));
    }

    /**
     * {@inheritDoc}
     */
    public static function getFileName(string $signature, bool $zip = false): string
    {
        return \sprintf($zip ? '%s.csv.zip' : '%s.csv', $signature);
    }

    /**
     * Check if a report file can be overwritten.
     *
     * @param string $fileName
     * @param int    $ttl
     *
     * @return bool
     */
    protected function fileCanBeOverwritten(string $fileName, int $ttl = 300): bool
    {
        if ($this->filesystem->exists($fileName)) {
            $lastModified = Carbon::createFromTimestamp($this->filesystem->lastModified($fileName));

            // Files older than the pre-defined TTL can be overwritten
            return $lastModified->diffInSeconds() > $ttl;
        }

        return true;
    }

    /**
     * Compress the report file in Zip format.
     *
     * @return bool
     */
    protected function compress(): bool
    {
        $zip = new ZipArchive();

        $zipFilePath    = $this->filesystem->url(static::getFileName($this->filter->getSignature(true), true));
        $reportFilePath = $this->filesystem->url($this->getTemporaryFileName());

        $zip->open($zipFilePath, ZipArchive::CREATE);
        $zip->addFile($reportFilePath, \sprintf('%s.csv', Str::snake(class_basename($this))));

        return $zip->close();
    }

    /**
     * {@inheritDoc}
     */
    public function generate(LoggerInterface $logger): bool
    {
        $temporaryFileName = $this->getTemporaryFileName();

        if (! $this->fileCanBeOverwritten($temporaryFileName)) {
            $logger->info(\sprintf('Already generating %s report. Exiting...', $temporaryFileName));

            return false;
        }

        $reportFileName = static::getFileName($this->filter->getSignature(true));

        $logger->info(\sprintf('Generating %s report...', $reportFileName));

        try {
            $temporaryFilePath = $this->filesystem->url($temporaryFileName);

            $writer = WriterEntityFactory::createCSVWriter();

            // Make sure we don't have UTF-8 BOM
            $writer->setShouldAddBOM(false);

            $writer->openToFile($temporaryFilePath);

            // Header
            $writer->addRow(WriterEntityFactory::createRowFromArray($this->getHeader()));

            // Data
            foreach ($this->getData() as $row) {
                $writer->addRow(WriterEntityFactory::createRowFromArray($row));
            }

            $writer->close();

            if ($this->compress()) {
                // Replace the old report file
                if ($this->filesystem->exists($reportFileName)) {
                    $this->filesystem->delete($reportFileName);
                }

                $logger->info(\sprintf('%s report generated.', $reportFileName));

                return $this->filesystem->move($temporaryFileName, $reportFileName);
            }
        } catch (SpoutException $e) {
            $logger->error('Report Generation Error', [
                'message' => $e->getMessage(),
            ]);
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getSignature(): string
    {
        return $this->filter->getSignature(true);
    }

    /**
     * {@inheritDoc}
     */
    public function isReadyForDownload(): bool
    {
        $zipFileName = static::getFileName($this->filter->getSignature(true), true);

        // A report file is considered ready for download if it can't be overwritten
        return $this->fileCanBeOverwritten($zipFileName) === false;
    }
}
