<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 17:01
 */
namespace Notadd\Foundation\Editor\Uploaders;

use Illuminate\Container\Container;

/**
 * Class UploadCatch.
 */
class UploadCatch extends AbstractUpload
{
    /**
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function doUpload()
    {
        $imgUrl = strtolower(str_replace('&amp;', '&', $this->config['imgUrl']));
        if (strpos($imgUrl, 'http') !== 0) {
            $this->stateInfo = $this->getStateInfo('ERROR_HTTP_LINK');

            return false;
        }
        $heads = get_headers($imgUrl);
        if (!(stristr($heads[0], '200') && stristr($heads[0], 'OK'))) {
            $this->stateInfo = $this->getStateInfo('ERROR_DEAD_LINK');

            return false;
        }
        $fileType = strtolower(strrchr($imgUrl, '.'));
        if (!in_array($fileType, $this->config['allowFiles'])) {
            $this->stateInfo = $this->getStateInfo('ERROR_HTTP_CONTENTTYPE');

            return false;
        }
        ob_start();
        $context = stream_context_create([
            'http' => [
                'follow_location' => false,
            ],
        ]);
        readfile($imgUrl, false, $context);
        $img = ob_get_contents();
        ob_end_clean();
        preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m);
        $this->oriName = $m ? $m[1] : '';
        $this->fileSize = strlen($img);
        $this->fileType = $this->getFileExt();
        $this->fullName = $this->getFullName();
        $this->filePath = $this->getFilePath();
        $this->fileName = basename($this->filePath);
        $dirName = dirname($this->filePath);
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getStateInfo('ERROR_SIZE_EXCEED');

            return false;
        }
        if (!file_exists($dirName) && !mkdir($dirName, 0777, true)) {
            $this->stateInfo = $this->getStateInfo('ERROR_CREATE_DIR');

            return false;
        } elseif (!is_writeable($dirName)) {
            $this->stateInfo = $this->getStateInfo('ERROR_DIR_NOT_WRITEABLE');

            return false;
        }
        if (!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) { //移动失败
            $this->stateInfo = $this->getStateInfo('ERROR_WRITE_CONTENT');

            return false;
        } else {
            if (Container::getInstance()->make('setting')->get('attachment.watermark',
                    false) && Container::getInstance()->make('files')->exists($this->config['watermark'])
            ) {
                $this->image->make($this->getFilePath())->insert($this->config['watermark'],
                    'center')->save($this->filePath);
            }
            $this->stateInfo = $this->stateMap[0];

            return true;
        }
    }

    /**
     * @return string
     */
    protected function getFileExt()
    {
        return strtolower(strrchr($this->oriName, '.'));
    }
}
