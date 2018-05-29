<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-25 17:47
 */
namespace Notadd\Foundation\Administration;

use InvalidArgumentException;
use Notadd\Foundation\Administration\Abstracts\Administrator;
use Notadd\Foundation\Administration\Repositories\NavigationRepository;
use Notadd\Foundation\Administration\Repositories\PageRepository;
use Notadd\Foundation\Administration\Repositories\ScriptRepository;
use Notadd\Foundation\Administration\Repositories\StylesheetRepository;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class AdministrationManager.
 */
class AdministrationManager
{
    use Helpers;

    /**
     * @var \Notadd\Foundation\Administration\Abstracts\Administrator
     */
    protected $administrator;

    /**
     * @var \Notadd\Foundation\Administration\Repositories\NavigationRepository
     */
    protected $navigationRepository;

    /**
     * @var \Notadd\Foundation\Administration\Repositories\PageRepository
     */
    protected $pageRepository;

    /**
     * @var \Notadd\Foundation\Administration\Repositories\ScriptRepository
     */
    protected $scriptRepository;

    /**
     * @var \Notadd\Foundation\Administration\Repositories\StylesheetRepository
     */
    protected $stylesheetRepository;

    /**
     * Get administrator.
     *
     * @return \Notadd\Foundation\Administration\Abstracts\Administrator
     */
    public function getAdministrator()
    {
        return $this->administrator;
    }

    /**
     * Status of administrator's instance.
     *
     * @return bool
     */
    public function hasAdministrator()
    {
        return is_null($this->administrator) ? false : true;
    }

    /**
     * Set administrator instance.
     *
     * @param \Notadd\Foundation\Administration\Abstracts\Administrator $administrator
     *
     * @throws \InvalidArgumentException
     */
    public function setAdministrator(Administrator $administrator)
    {
        if (is_object($this->administrator)) {
            throw new InvalidArgumentException('Administrator has been Registered!');
        }
        if ($administrator instanceof Administrator) {
            $this->administrator = $administrator;
            $this->administrator->init();
        } else {
            throw new InvalidArgumentException('Administrator must be instanceof ' . Administrator::class . '!');
        }
    }

    /**
     * @return \Notadd\Foundation\Administration\Repositories\NavigationRepository
     */
    public function navigations()
    {
        if (!$this->navigationRepository instanceof NavigationRepository) {
            $this->navigationRepository = new NavigationRepository();
            $this->navigationRepository->initialize($this->module->menus()->structures());
        }

        return $this->navigationRepository;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function pages()
    {
        if (!$this->pageRepository instanceof PageRepository) {
            $this->pageRepository = new PageRepository();
            $this->pageRepository->initialize(collect());
        }

        return $this->pageRepository;
    }

    /**
     * @return \Notadd\Foundation\Administration\Repositories\ScriptRepository
     */
    public function scripts()
    {
        if (!$this->scriptRepository instanceof ScriptRepository) {
            $this->scriptRepository = new ScriptRepository();
            $this->scriptRepository->initialize(collect());
        }

        return $this->scriptRepository;
    }

    /**
     * @return \Notadd\Foundation\Administration\Repositories\StylesheetRepository
     */
    public function stylesheets()
    {
        if (!$this->stylesheetRepository instanceof  StylesheetRepository) {
            $this->stylesheetRepository = new StylesheetRepository();
            $this->stylesheetRepository->initialize(collect());
        }

        return $this->stylesheetRepository;
    }
}
