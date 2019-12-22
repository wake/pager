<?php

  namespace Pager;


  /**
   *
   * Pager - ORM pagination wrapper
   *
   */
  class Pager {


    /**
     *
     * Setting
     *
     */
    var $setting = [

      'item' => [

        // show - How many items list per page
        'show' => 15,

        // total - Item amount
        'total' => null,
      ],

      'page' => [

        // size - How many pages display on
        'size' => 10,

        // current - Current page
        'current' => 1,

        // total - Page amount
        'total' => 1,

        // dynamic - Display pages dynamically or not
        'dynamic' => true,
      ],

      'url' => [

        // type of generator
        'type' => 'pattern',

        // pattern
        'pattern' => '(:num)',

        // function
        'func' => null,

        // url string
        'string' => null,
      ],
    ];


    /**
     *
     * Pages
     *
     */
    public $pages;


    /**
     *
     * Construct
     *
     */
    function __construct () {

      $this->pages = new PageItems ($this);

      return $this;
    }


    /**
     *
     * Isset
     *
     */
    public function __isset ($name) {

      if (in_array ($name, ['show', 'total', 'size', 'page', 'dynamic', 'fixed']))
        return true;

      return isset ($this->$name);
    }


    /**
     *
     * Short access
     *
     */
    public function __get ($name) {

      switch ($name) {

        // Ref to settings
        case 'show':
          return $this->setting['item']['show'];
          break;

        case 'total':
          return $this->setting['item']['total'];
          break;

        // Ref to pages
        case 'size':
          return $this->setting['page']['size'];
          break;

        case 'page':
          return $this->pages->current;
          break;

        case 'dynamic':
          return $this->pages->dynamic;
          break;

        case 'fixed':
          return $this->pages->fixed;
          break;
      }
    }


    /**
     *
     * Short access
     *
     */
    public function __set ($name, $value) {

      switch ($name) {

        // Ref to settings
        case 'show':
          return $this->show ($value);
          break;

        case 'total':
          return $this->total ($value);
          break;

        // Ref to pages
        case 'size':
          return $this->size ($value);
          break;

        case 'page':
          return $this->page ($value);
          break;

        case 'dynamic':
          return $this->dynamic ($value);
          break;

        case 'fixed':
          return $this->fixed ($value);
          break;
      }

      return $this;
    }


    /**
     *
     * Set item display amount
     *
     */
    public function show ($show) {
      $this->setting['item']['show'] = $show < 1 ? 1 : $show;
      return $this;
    }


    /**
     *
     * Set item amount
     *
     */
    public function total ($total) {
      $this->setting['item']['total'] = $total < 0 ? 0 : $total;
      return $this;
    }


    /**
     *
     * Set page size
     *
     */
    public function size ($size) {
      $this->setting['page']['size'] = $size < 1 ? 1 : $size;
      return $this;
    }


    /**
     *
     * Set current page
     *
     */
    public function page ($page) {
      $this->setting['page']['current'] = $page;
      return $this;
    }


    /**
     *
     * Set page items displayed dynamically or fixed
     *
     */
    public function dynamic ($dynamic = true) {
      $this->setting['page']['dynamic'] = $dynamic === true;
      return $this;
    }


    /**
     *
     * Set page items displayed dynamically or fixed
     *
     */
    public function fixed ($fixed = true) {
      $this->setting['page']['dynamic'] = $fixed !== true;
      return $this;
    }


    /**
     *
     * Setting / Generote URL
     *
     */
    public function url ($para) {

      if (is_string ($para)) {
        $this->setting['url']['type'] = 'pattern';
        $this->setting['url']['string'] = $para;
      }

      else if (is_callable ($para)) {
        $this->setting['url']['type'] = 'func';
        $this->setting['url']['func'] = $para;
      }

      else if (is_object ($para) && get_class ($para) == 'Pager\PageItem' || is_subclass_of ($para, 'Pager\PageItem')) {

        if ($this->setting['url']['type'] == 'pattern')
          return str_replace ([$this->setting['url']['pattern'], rawurlencode ($this->setting['url']['pattern'])], $para->num, $this->setting['url']['string']);

        else if ($this->setting['url']['type'] == 'func') {
          $func = $this->setting['url']['func'];
          return $func ($para->num);
        }
      }

      return $this;
    }


    /**
     *
     * Paging
     *
     */
    public function paging () {

      $itemShow = $this->setting['item']['show'];
      $totalItems = $this->setting['item']['total'];
      $pageCurrent = $this->setting['page']['current'];

      $totalPages = ceil ($totalItems / $itemShow);

      if ($totalPages == 0)
        $totalPages = 1;

      if ($pageCurrent > $totalPages)
        $pageCurrent = $totalPages;

      // Count with page size
      $pageSize = min ($this->setting['page']['size'], $totalPages);

      if ($this->setting['page']['dynamic'])
        $result = $this->pagingDynamic ($itemShow, $totalItems, $totalPages, $pageCurrent, $pageSize);

      else
        $result = $this->pagingFixed ($itemShow, $totalItems, $totalPages, $pageCurrent, $pageSize);

      $this->setting['page']['total'] = $totalPages;

      $this->pages->top = 1;
      $this->pages->end = $totalPages;
      $this->pages->first = $result['pageFirst'];
      $this->pages->last = $result['pageLast'];
      $this->pages->prev = $pageCurrent - 1 <= 1 ? 1 : $pageCurrent - 1;
      $this->pages->next = $pageCurrent + 1 >= $totalPages ? $totalPages : $pageCurrent + 1;
      $this->pages->current = $pageCurrent;
      $this->pages->total = $totalPages;
      $this->pages->build ();
    }

    /**
     *
     * Paging dynamically
     *
     */
    public function pagingDynamic ($itemShow, $totalItems, $totalPages, $pageCurrent, $pageSize) {

      $pageCurrentBefore = ceil ($pageSize / 2);
      $pageCurrentAfter = $pageSize - $pageCurrentBefore - 1;
      $pageOffset = 0;

      $pageFirst = $pageCurrent - $pageCurrentBefore;

      // Get offset from `page start` to `current page`
      if ($pageFirst < 1) {
        $pageOffset = abs ($pageFirst) + 1;
        $pageFirst = 1;
      }

      // Make sure there is enough page items after current page
      $pageLast = $pageCurrent + $pageCurrentAfter + $pageOffset;

      // Reset offset from `current page` to `page end` if exceed
      if ($pageLast > $totalPages) {

        $pageOffset = $pageLast - $totalPages;
        $pageLast = $totalPages;
        $pageFirst = max ($pageFirst - $pageOffset, 1);
      }

      return [
        'pageFirst' => $pageFirst,
        'pageLast'  => $pageLast,
        ];
    }


    /**
     *
     * Paging fixed
     *
     */
    public function pagingFixed ($itemShow, $totalItems, $totalPages, $pageCurrent, $pageSize) {

      $pageGroupNo = ceil ($pageCurrent / $pageSize) - 1;

      $pageFirst = $pageGroupNo * $pageSize + 1;
      $pageLast = min ($pageGroupNo * $pageSize + $pageSize, $totalPages);

      if ((($pageLast - $pageFirst) + 1 < $pageSize) && $pageLast > $pageSize)
        $pageFirst = max ($pageLast - $pageSize + 1, 1);

      return [
        'pageFirst' => $pageFirst,
        'pageLast'  => $pageLast,
        ];
    }
  }
