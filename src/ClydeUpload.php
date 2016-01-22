<?php

namespace Antennaio\Clyde;

use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Contracts\Filesystem\FilesystemAdapter;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ClydeUpload
{
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
     * @param Filesystem $files
     * @param FilenameGenerator $filename
     */
    public function __construct(Filesystem $files, FilenameGenerator $filename)
    {
        $this->files = $files;
        $this->filename = $filename;

        $this->setDisk('local');
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
        return config('clyde.source_path_prefix') . DIRECTORY_SEPARATOR . $filename;
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
