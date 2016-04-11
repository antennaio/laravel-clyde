<?php

namespace Antennaio\Clyde;

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
     * @param UploadedFile $file
     * @param string       $filename
     *
     * @return string
     */
    public function upload(UploadedFile $file, $filename = null)
    {
        $filename = ($filename) ? $filename : $this->filename->generate($file);

        $this->disk->put(
            $this->buildPath($filename),
            fopen($file->getRealPath(), 'r+')
        );

        return $filename;
    }

    /**
     * Delete a file.
     *
     * @param string $filename
     */
    public function delete($filename)
    {
        $this->disk->delete($this->buildPath($filename));
    }

    /**
     * Check if a file exists.
     *
     * @param string $filename
     *
     * @return bool
     */
    public function exists($filename)
    {
        return empty($filename) ? false : $this->disk->exists($this->buildPath($filename));
    }

    /**
     * Build path.
     *
     * @param string $filename
     *
     * @return string
     */
    protected function buildPath($filename)
    {
        return $this->config->get('clyde.source_path_prefix').DIRECTORY_SEPARATOR.$filename;
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
