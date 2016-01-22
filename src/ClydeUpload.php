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
     * @param Config $config
     * @param Filesystem $files
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
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $filename = $this->filename->generate($file);

        $this->disk->put(
            $this->buildPath($filename),
            fopen($file->getRealPath(), 'r+')
        );

        return $filename;
    }

    /**
     * Remove a file.
     *
     * @param string $filename
     * @return void
     */
    public function remove($filename)
    {
        $this->disk->delete($this->buildPath($filename));
    }

    /**
     * Build path.
     *
     * @param string $filename
     * @return string
     */
    protected function buildPath($filename)
    {
        return $this->config->get('clyde.source_path_prefix') . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Set disk.
     *
     * @param string $disk
     * @return void
     */
    public function setDisk($disk)
    {
        $this->disk = $this->files->disk($disk);
    }

    /**
     * Get disk.
     *
     * @return string
     * @return void
     */
    public function getDisk()
    {
        return $this->disk;
    }
}
