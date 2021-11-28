<?php

  namespace Pager;


  /**
   *
   * Config
   *
   */
  class Core extends Config {


    /**
     *
     * Collection of pages, public and accessable
     *
     */
    public $pages;


    /**
     *
     * Construct
     *
     */
    public function __construct () {
      $this->pages = new Collection ($this);
      return $this;
    }


    /**
     *
     * Isset (proxy to collection class)
     *
     */
    public function __isset ($name) {

      if (parent::__isset ($name))
        return true;

      if ($this->pages->__isset ($name))
        return true;

      return false;
    }


    /**
     *
     * Get (proxy to collection class)
     *
     */
    public function __get ($name) {

      if (parent::__isset ($name))
        return parent::__get ($name);

      if ($this->pages->__isset ($name))
        return $this->pages->__get ($name);

      return isset ($this->$name) ? $this->$name : null;
    }


    /**
     *
     * Set (proxy to collection class)
     *
     */
    public function __set ($name, $value) {

      if (parent::__isset ($name))
        parent::__set ($name, $value);

      if ($this->pages->__isset ($name))
        $this->pages->__set ($name, $value);

      $this->$name = $value;
    }


    /**
     *
     * Interception functions for ORM class
     *
     */
    public function __call ($name, $args) {

      if (isset ($this->$name)) {

        $item = $this->$name;

        if (is_callable ($item))
          return call_user_func_array ($item, $args);
      }

      return null;
    }


    /**
     *
     * Count
     *
     */
    public function count () {
      return 0;
    }


    /**
     *
     * Rows
     *
     */
    public function rows ($page) {
      return [];
    }


    /**
     *
     * Paging
     *
     */
    public function paging () {

      $this->amount ($this->count ());

      $rowAmount = $this->_setting['row']['amount'];
      $rowLimit = $this->_setting['row']['limit'];
      $pageCurrent = $this->_setting['page']['_current'];

      $pageTotal = ceil ($rowAmount / $rowLimit);
      $pageCurrent = min (max ($pageCurrent, 1), $pageTotal);

      // Count with page size
      $pageSize = min ($this->_setting['page']['size'], $pageTotal);

      if ($this->_setting['page']['dynamic'])
        $paging = $this->dynamicPaging ($pageTotal, $pageCurrent, $pageSize);

      else
        $paging = $this->fixedPaging ($pageTotal, $pageCurrent, $pageSize);

      $this->pages->reset ()->paging ([
        'top'     => 1,
        'end'     => $pageTotal,
        'current' => $pageCurrent,
        'group'   => range ($paging['first'], $paging['last']),
      ]);

      return $this;
    }


    /**
     *
     * Paging using dynamic algorithm
     *
     */
    public function dynamicPaging ($pageTotal, $pageCurrent, $pageSize) {

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
      if ($pageLast > $pageTotal) {

        $pageOffset = $pageLast - $pageTotal;
        $pageLast = $pageTotal;
        $pageFirst = max ($pageFirst - $pageOffset, 1);
      }

      return [
        'first' => intval ($pageFirst),
        'last'  => intval ($pageLast),
      ];
    }


    /**
     *
     * Paging using fixed algorithm
     *
     */
    public function fixedPaging ($pageTotal, $pageCurrent, $pageSize) {

      $pageGroupNo = ceil ($pageCurrent / $pageSize) - 1;

      $pageFirst = $pageGroupNo * $pageSize + 1;
      $pageLast = min ($pageGroupNo * $pageSize + $pageSize, $pageTotal);

      if ((($pageLast - $pageFirst) + 1 < $pageSize) && $pageLast > $pageSize)
        $pageFirst = max ($pageLast - $pageSize + 1, 1);

      return [
        'first' => intval ($pageFirst),
        'last'  => intval ($pageLast),
      ];
    }
  }
