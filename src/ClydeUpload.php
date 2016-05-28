<?php

namespace Antennaio\Clyde;

use Closure;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Contracts\Filesystem\FilesystemAdapter;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ClydeUpload
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var FilenameGenerator
     */
    protected $filename;

    /**
     * @var FilesystemAdapter
     */
    protected $disk;

    /**
     * Create a new ClydeUpload instance.
     *
     * @param Config            $config
     * @param Filesystem        $files
     * @param FilenameGenerator $filename
     */
    public function __construct(Config $config, Filesystem $files, FilenameGenerator $filename)
    {
        $this->config = $config;
        $this->files = $files;
        $this->filename = $filename;

        $this->setDisk($this->config->get('clyde.source'));
    }

    /**
     * Upload a file.
     *
     * @param UploadedFile         $file
     * @param \Closure|string|null $filePath
     *
     * @return string
     */
    public function upload(UploadedFile $file, $filePath = null)
    {
        $filePath = $this->resolveFilePath($filePath, $this->filename->generate($file));

        $this->disk->put(
            $this->buildPath($filePath),
            fopen($file->getRealPath(), 'r+')
        );

        return $filePath;
    }

    /**
     * Resolve file path.
     *
     * @param \Closure|string|null $filePath
     * @param string               $suggestedFilename
     *
     * @return string
     */
    protected function resolveFilePath($filePath, $suggestedFilename)
    {
        if ($filePath instanceof Closure) {
            $filePath = $filePath($suggestedFilename);
        }

        return is_null($filePath) ? $suggestedFilename : $filePath;
    }

    /**
     * Delete a file.
     *
     * @param string $filePath
     */
    public function delete($filePath)
    {
        $this->disk->delete($this->buildPath($filePath));
    }

    /**
     * Check if a file exists.
     *
     * @param string $filePath
     *
     * @return bool
     */
    public function exists($filePath)
    {
        if (empty($filePath)) {
            return false;
        }

        return $this->disk->exists($this->buildPath($filePath));
    }

    /**
     * Build path.
     *
     * @param string $filePath
     *
     * @return string
     */
    protected function buildPath($filePath)
    {
        return $this->config->get('clyde.source_path_prefix').DIRECTORY_SEPARATOR.$filePath;
    }

    /**
     * Set disk.
     *
     * @param string $disk
     */
    public function setDisk($disk)
    {
        $this->disk = $this->files->disk($disk);
    }

    /**
     * Get disk.
     *
     * @return string
     */
    public function getDisk()
    {
        return $this->disk;
    }
}
