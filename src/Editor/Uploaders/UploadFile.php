<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 17:02
 */
namespace Notadd\Foundation\Editor\Uploaders;

use Illuminate\Container\Container;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Class UploadFile.
 */
class UploadFile extends AbstractUpload
{
    /**
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function doUpload()
    {
        $file = $this->request->file($this->fileField);
        if (empty($file)) {
            $this->stateInfo = $this->getStateInfo('ERROR_FILE_NOT_FOUND');

            return false;
        }
        if (!$file->isValid()) {
            $this->stateInfo = $this->getStateInfo($file->getError());

            return false;
        }
        $this->file = $file;
        $this->oriName = $this->file->getClientOriginalName();
        $this->fileSize = $this->file->getSize();
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = basename($this->filePath);
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo('ERROR_SIZE_EXCEED');

            return false;
        }
        if (!$this->checkType()) {
            $this->stateInfo = $this->getStateInfo('ERROR_TYPE_NOT_ALLOWED');

            return false;
        }
        try {
            $this->file->move(dirname($this->filePath), $this->fileName);
            if (Container::getInstance()->make('setting')->get('attachment.watermark',
                    false) && Container::getInstance()->make('files')->exists($this->config['watermark'])
            ) {
                $this->image->make($this->getFilePath())->insert($this->config['watermark'],
                    'center')->save($this->filePath);
            }
            $this->stateInfo = $this->stateMap[0];
        } catch (FileException $exception) {
            $this->stateInfo = $this->getStateInfo('ERROR_WRITE_CONTENT');

            return false;
        }

        return true;
    }
}
