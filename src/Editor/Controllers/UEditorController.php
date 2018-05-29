<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 16:50
 */
namespace Notadd\Foundation\Editor\Controllers;

use Illuminate\Http\Request;
use Notadd\Foundation\Editor\Lists;
use Notadd\Foundation\Editor\Uploaders\UploadCatch;
use Notadd\Foundation\Editor\Uploaders\UploadFile;
use Notadd\Foundation\Editor\Uploaders\UploadScrawl;
use Notadd\Foundation\Routing\Abstracts\Controller;

/**
 * Class UEditorController.
 */
class UEditorController extends Controller
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var \Notadd\Foundation\Image\ImageManager
     */
    protected $image;

    /**
     * @var \Notadd\Foundation\Setting\Contracts\SettingsRepository
     */
    protected $setting;

    /**
     * UEditorController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->image = $this->container->make('images');
        $this->setting = $this->setting();
        $this->config();
    }

    protected function config()
    {
        $this->config = [
            'imageActionName'         => 'uploadimage',
            'imageFieldName'          => 'upfile',
            'imageMaxSize'            => $this->setting->get('attachment.limit.image', 2048) * 1000,
            'imageAllowFiles'         => explode(',', $this->setting->get('attachment.format.image', '.png,.jpg,.jpeg,.gif,.bmp')),
            'imageCompressEnable'     => true,
            'imageCompressBorder'     => 1600,
            'imageInsertAlign'        => 'none',
            'imageUrlPrefix'          => '',
            'imagePathFormat'         => '/uploads/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
            'scrawlActionName'        => 'uploadscrawl',
            'scrawlFieldName'         => 'upfile',
            'scrawlPathFormat'        => '/uploads/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
            'scrawlMaxSize'           => $this->setting->get('attachment.limit.image', 2048) * 1000,
            'scrawlUrlPrefix'         => '',
            'scrawlInsertAlign'       => 'none',
            'snapscreenActionName'    => 'uploadimage',
            'snapscreenPathFormat'    => '/uploads/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
            'snapscreenUrlPrefix'     => '',
            'snapscreenInsertAlign'   => 'none',
            'catcherLocalDomain'      => [
                '127.0.0.1',
                'localhost',
                'img.baidu.com',
            ],
            'catcherActionName'       => 'catchimage',
            'catcherFieldName'        => 'source',
            'catcherPathFormat'       => '/uploads/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}',
            'catcherUrlPrefix'        => '',
            'catcherMaxSize'          => $this->setting->get('attachment.limit.image', 2048) * 1000,
            'catcherAllowFiles'       => explode(',', $this->setting->get('attachment.format.catcher', '.png,.jpg,.jpeg,.gif,.bmp')),
            'videoActionName'         => 'uploadvideo',
            'videoFieldName'          => 'upfile',
            'videoPathFormat'         => '/uploads/ueditor/php/upload/video/{yyyy}{mm}{dd}/{time}{rand:6}',
            'videoUrlPrefix'          => '',
            'videoMaxSize'            => $this->setting->get('attachment.limit.video', 2048) * 1000,
            'videoAllowFiles'         => $this->setting->get('attachment.format.video', '.flv,.swf,.mkv,.avi,.rm,.rmvb,.mpeg,.mpg,.ogg,.ogv,.mov,.wmv,.mp4,.webm,.mp3,.wav,.mid'),
            'fileActionName'          => 'uploadfile',
            'fileFieldName'           => 'upfile',
            'filePathFormat'          => '/uploads/ueditor/php/upload/file/{yyyy}{mm}{dd}/{time}{rand:6}',
            'fileUrlPrefix'           => '',
            'fileMaxSize'             => $this->setting->get('attachment.limit.file', 2048) * 1000,
            'fileAllowFiles'          => explode(',', $this->setting->get('attachment.format.file', '.png,.jpg,.jpeg,.gif,.bmp,.flv,.swf,.mkv,.avi,.rm,.rmvb,.mpeg,.mpg,.ogg,.ogv,.mov,.wmv,.mp4,.webm,.mp3,.wav,.mid,.rar,.zip,.tar,.gz,.7z,.bz2,.cab,.iso,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.txt,.md,.xml')),
            'imageManagerActionName'  => 'listimage',
            'imageManagerListPath'    => '/uploads/ueditor/php/upload/image/',
            'imageManagerListSize'    => 20,
            'imageManagerUrlPrefix'   => '',
            'imageManagerInsertAlign' => 'none',
            'imageManagerAllowFiles'  => explode(',', $this->setting->get('attachment.manager.image', ".png,.jpg,.jpeg,.gif,.bmp")),
            'fileManagerActionName'   => 'listfile',
            'fileManagerListPath'     => '/uploads/ueditor/php/upload/file/',
            'fileManagerUrlPrefix'    => '',
            'fileManagerListSize'     => 20,
            'fileManagerAllowFiles'   => $this->setting->get('attachment.manager.image'),
            'watermark'               => asset($this->setting->get('attachment.watermark.file', 'watermark.png')),
        ];
    }

    /**
     * Handler.
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(Request $request)
    {
        $action = $request->get('action');
        $result = [];
        switch ($action) {
            case 'config':
                $result = $this->config;
                break;
            case 'uploadimage':
                $config = [
                    'pathFormat' => $this->config['imagePathFormat'],
                    'maxSize'    => $this->config['imageMaxSize'],
                    'allowFiles' => $this->config['imageAllowFiles'],
                    'fieldName'  => $this->config['imageFieldName'],
                    'watermark'  => $this->config['watermark'],
                ];
                $result = with(new UploadFile($config, $request, $this->image))->upload();
                break;
            case 'uploadscrawl':
                $config = [
                    'pathFormat' => $this->config['scrawlPathFormat'],
                    'maxSize'    => $this->config['scrawlMaxSize'],
                    'oriName'    => 'scrawl.png',
                    'fieldName'  => $this->config['scrawlFieldName'],
                    'watermark'  => $this->config['watermark'],
                ];
                $result = with(new UploadScrawl($config, $request, $this->image))->upload();
                break;
            case 'uploadvideo':
                $config = [
                    'pathFormat' => $this->config['videoPathFormat'],
                    'maxSize'    => $this->config['videoMaxSize'],
                    'allowFiles' => $this->config['videoAllowFiles'],
                    'fieldName'  => $this->config['videoFieldName'],
                    'watermark'  => $this->config['watermark'],
                ];
                $result = with(new UploadFile($config, $request, $this->image))->upload();
                break;
            case 'uploadfile':
                $config = [
                    'pathFormat' => $this->config['filePathFormat'],
                    'maxSize'    => $this->config['fileMaxSize'],
                    'allowFiles' => $this->config['fileAllowFiles'],
                    'fieldName'  => $this->config['fileFieldName'],
                    'watermark'  => $this->config['watermark'],
                ];
                $result = with(new UploadFile($config, $request, $this->image))->upload();
                break;
            case 'listimage':
                $result = with(new Lists(
                    $this->config['imageManagerAllowFiles'],
                    $this->config['imageManagerListSize'],
                    $this->config['imageManagerListPath'],
                    $request))->getList();
                break;
            case 'listfile':
                $result = with(new Lists(
                    $this->config['fileManagerAllowFiles'],
                    $this->config['fileManagerListSize'],
                    $this->config['fileManagerListPath'],
                    $request))->getList();
                break;
            case 'catchimage':
                $config = [
                    'pathFormat' => $this->config['catcherPathFormat'],
                    'maxSize'    => $this->config['catcherMaxSize'],
                    'allowFiles' => $this->config['catcherAllowFiles'],
                    'oriName'    => 'remote.png',
                    'fieldName'  => $this->config['catcherFieldName'],
                    'watermark'  => $this->config['watermark'],
                ];
                $sources = $request->input($config['fieldName']);
                $list = [];
                foreach ((array)$sources as $imgUrl) {
                    $config['imgUrl'] = $imgUrl;
                    $info = with(new UploadCatch($config, $request, $this->image))->upload();
                    array_push($list, [
                        'state'    => $info['state'],
                        'url'      => $info['url'],
                        'size'     => $info['size'],
                        'title'    => htmlspecialchars($info['title']),
                        'original' => htmlspecialchars($info['original']),
                        'source'   => htmlspecialchars($imgUrl),
                    ]);
                }
                $result = [
                    'state' => count($list) ? 'SUCCESS' : 'ERROR',
                    'list'  => $list,
                ];
                break;
        }

        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
