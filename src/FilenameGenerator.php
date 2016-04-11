<?php

namespace Antennaio\Clyde;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FilenameGenerator
{
    /**
     * Generate a filename.
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    public function generate(UploadedFile $file)
    {
        return uniqid().'.'.$file->getClientOriginalExtension();
    }
}
