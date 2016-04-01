<?php

namespace Antennaio\Clyde\Test;

use Antennaio\Clyde\ClydeUpload;
use Antennaio\Clyde\FilenameGenerator;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Contracts\Filesystem\FilesystemAdapter;
use Mockery;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ClydeUploadTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->files = Mockery::mock(Filesystem::class);
        $this->disk = Mockery::mock(FilesystemAdapter::class);
        $this->filename = Mockery::mock(FilenameGenerator::class);
        $this->uploadedFile = Mockery::mock(UploadedFile::class);
        $this->config = Mockery::mock(Config::class);

        $this->files->shouldReceive('disk')->once()->andReturn($this->disk);
        $this->config->shouldReceive('get')->with('clyde.source')->once()->andReturn('local');
        $this->config->shouldReceive('get')->with('clyde.source_path_prefix')->once()->andReturn('/uploads');

        $this->uploads = new ClydeUpload($this->config, $this->files, $this->filename);
    }

    public function testUpload()
    {
        $this->uploadedFile->shouldReceive('getRealPath')->once()->andReturn('php://memory');
        $this->filename->shouldReceive('generate')->andReturn('56a1472beca5d.jpg');
        $this->disk->shouldReceive('put')->once();

        $filename = $this->uploads->upload($this->uploadedFile);

        $this->assertEquals('56a1472beca5d.jpg', $filename);
    }
}
