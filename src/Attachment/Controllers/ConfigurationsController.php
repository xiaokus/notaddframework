<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-11 16:28
 */
namespace Notadd\Foundation\Attachment\Controllers;

use Illuminate\Http\JsonResponse;
use Notadd\Foundation\Routing\Abstracts\Controller;

/**
 * Class ConfigurationsController.
 */
class ConfigurationsController extends Controller
{
    /**
     * @var array
     */
    protected $files = [
        '.png',
        '.jpg',
        '.jpeg',
        '.gif',
        '.bmp',
        '.flv',
        '.swf',
        '.mkv',
        '.avi',
        '.rm',
        '.rmvb',
        '.mpeg',
        '.mpg',
        '.ogg',
        '.ogv',
        '.mov',
        '.wmv',
        '.mp4',
        '.webm',
        '.mp3',
        '.wav',
        '.mid',
        '.rar',
        '.zip',
        '.tar',
        '.gz',
        '.7z',
        '.bz2',
        '.cab',
        '.iso',
        '.doc',
        '.docx',
        '.xls',
        '.xlsx',
        '.ppt',
        '.pptx',
        '.pdf',
        '.txt',
        '.md',
        '.xml',
    ];

    /**
     * @var array
     */
    protected $images = [
        '.png',
        '.jpg',
        '.jpeg',
        '.gif',
        '.bmp',
    ];

    /**
     * @var array
     */
    protected $videos = [
        '.flv',
        '.swf',
        '.mkv',
        '.avi',
        '.rm',
        '.rmvb',
        '.mpeg',
        '.mpg',
        '.ogg',
        '.ogv',
        '.mov',
        '.wmv',
        '.mp4',
        '.webm',
        '.mp3',
        '.wav',
        '.mid',
    ];

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(): JsonResponse
    {
        return $this->response->json()->setData([
            'data'    => [
                'canManagementImageExtension' => $this->setting->get('attachment.manager.image', implode(',', $this->images)),
                'canManagementFileExtension'  => $this->setting->get('attachment.manager.file', implode(',', $this->files)),
                'canUploadImageExtension'     => $this->setting->get('attachment.format.image', implode(',', $this->images)),
                'canUploadCatcherExtension'   => $this->setting->get('attachment.format.catcher', implode(',', $this->images)),
                'canUploadVideoExtension'     => $this->setting->get('attachment.format.video', implode(',', $this->videos)),
                'canUploadFileExtension'      => $this->setting->get('attachment.format.file', implode(',', $this->files)),
                'fileMaxSize'                 => $this->setting->get('attachment.limit.file', 2048),
                'imageMaxSize'                => $this->setting->get('attachment.limit.image', 2048),
                'imageProcessingEngine'       => $this->setting->get('attachment.engine', 'gd'),
                'videoMaxSize'                => $this->setting->get('attachment.limit.video', 2048),
            ],
            'message' => '获取上传配置成功！',
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(): JsonResponse
    {
        $this->setting->set('attachment.engine', $this->request->get('imageProcessingEngine'));
        $this->setting->set('attachment.format.catcher', $this->request->get('canUploadCatcherExtension'));
        $this->setting->set('attachment.format.file', $this->request->get('canUploadFileExtension'));
        $this->setting->set('attachment.format.image', $this->request->get('canUploadImageExtension'));
        $this->setting->set('attachment.format.video', $this->request->get('canUploadVideoExtension'));
        $this->setting->set('attachment.limit.file', $this->request->get('fileMaxSize'));
        $this->setting->set('attachment.limit.image', $this->request->get('imageMaxSize'));
        $this->setting->set('attachment.limit.video', $this->request->get('videoMaxSize'));
        $this->setting->set('attachment.manager.file', $this->request->get('canManagementFileExtension'));
        $this->setting->set('attachment.manager.image', $this->request->get('canManagementImageExtension'));
        $this->setting->set('attachment.watermark', $this->request->get('allow_watermark'));

        return $this->response->json()->setData([
            'message' => '更新上传配置成功！',
        ]);
    }
}
