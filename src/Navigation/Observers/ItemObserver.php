<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-16 17:08
 */
namespace Notadd\Foundation\Navigation\Observers;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Notadd\Foundation\Navigation\Models\Item;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ItemObserver.
 */
class ItemObserver
{
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $file;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * ArticleObserver constructor.
     *
     * @param \Illuminate\Container\Container   $container
     * @param \Illuminate\Filesystem\Filesystem $file
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(Container $container, Filesystem $file)
    {
        $this->container = $container;
        $this->file = $file;
        $this->request = $container->make('request');
    }

    /**
     * Article creating handler.
     *
     * @param \Notadd\Foundation\Navigation\Models\Item $item
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function creating(Item $item)
    {
        $thumbImage = $this->request->file('image');
        if ($thumbImage) {
            if ($thumbImage instanceof UploadedFile) {
                $hash = hash_file('md5', $thumbImage->getPathname(), false);
                $dictionary = $this->pathSplit($hash, '12', Collection::make([
                    static_path(),
                    'uploads',
                ]))->implode(DIRECTORY_SEPARATOR);
                $file = Str::substr($hash, 12, 20) . '.' . $thumbImage->getClientOriginalExtension();
                if (!$this->file->exists($dictionary . DIRECTORY_SEPARATOR . $file)) {
                    $thumbImage->move($dictionary, $file);
                }
                $item->setAttribute('thumb_image', $this->pathSplit($hash, '12,20', Collection::make([
                        'uploads',
                    ]))->implode('/') . '.' . $thumbImage->getClientOriginalExtension());
            }
        }
    }

    /**
     * String split handler.
     *
     * @param string $path
     * @param string $dots
     * @param null   $data
     *
     * @return \Illuminate\Support\Collection|null
     */
    protected function pathSplit($path, $dots, $data = null)
    {
        $dots = explode(',', $dots);
        $data = $data ? $data : new Collection();
        $offset = 0;
        foreach ($dots as $dot) {
            $data->push(Str::substr($path, $offset, $dot));
            $offset += $dot;
        }

        return $data;
    }

    /**
     * Article updating handler.
     *
     * @param \Notadd\Foundation\Navigation\Models\Item $item
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function updating(Item $item)
    {
        $thumbImage = $this->request->file('image');
        if ($thumbImage) {
            if ($thumbImage instanceof UploadedFile) {
                $hash = hash_file('md5', $thumbImage->getPathname(), false);
                $dictionary = $this->pathSplit($hash, '12', Collection::make([
                    static_path(),
                    'uploads',
                ]))->implode(DIRECTORY_SEPARATOR);
                $file = Str::substr($hash, 12, 20) . '.' . $thumbImage->getClientOriginalExtension();
                if (!$this->file->exists($dictionary . DIRECTORY_SEPARATOR . $file)) {
                    $thumbImage->move($dictionary, $file);
                }
                $item->setAttribute('thumb_image', $this->pathSplit($hash, '12,20', Collection::make([
                        'uploads',
                    ]))->implode('/') . '.' . $thumbImage->getClientOriginalExtension());
            }
        }
    }
}
