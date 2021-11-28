<?php

  namespace Pager;


  /**
   *
   * Page item
   *
   */
  class Item {


    /**
     *
     * Pager adapter
     *
     */
    protected $adapter;


    /**
     *
     * Num - Page num
     *
     */
    protected $num;


    /**
     *
     * Active - If page is active
     *
     */
    protected $active = false;


    /**
     *
     * Counstruct
     *
     */
    public function __construct ($adapter, $num = null) {
      $this->adapter = $adapter;
      $this->num = is_null ($num) ? null : intval ($num);
    }


    /**
     *
     * Echo page number
     *
     */
    public function __toString () {
      return strval (! is_null ($this->num) ? $this->num : '');
    }


    /**
     *
     * Echo page number
     *
     */
    public function __invoke () {
      return strval (! is_null ($this->num) ? $this->num : '');
    }


    /**
     *
     * Setting
     *
     */
    public function __isset ($name) {

      if (in_array ($name, ['num', 'val', 'value', 'enable', 'active', 'isActive', 'isCurrent', 'url']))
        return true;

      return false;
    }


    /**
     *
     * Getting
     *
     */
    public function __get ($name) {

      switch ($name) {
        case 'num':
        case 'val':
        case 'value':
          return $this->num;

        case 'enable':
        case 'active':
        case 'isActive':
        case 'isCurrent':
          return $this->active;

        case 'url':
          return $this->url ();
      }
    }


    /**
     *
     * Setting
     *
     */
    public function __set ($name, $value) {

      switch ($name) {

        case 'num':
        case 'val':
        case 'value':
          $this->num ($value);
          break;

        case 'enable':
        case 'active':
        case 'current':
          return $this->active ($value);
          break;
      }
    }


    /**
     *
     * Set page number
     *
     */
    public function num ($num) {
      $this->num = ! is_null ($num) ? intval ($num) : $num;
      return $this;
    }


    /**
     *
     * Set page state of active
     *
     */
    public function active ($active = true) {
      $this->active = !! $active;
      return $this;
    }


    /**
     *
     * Generate URL
     *
     */
    public function url () {
      return $this->adapter->url ($this->num);
    }


    /**
     *
     * Get rows
     *
     */
    public function rows () {
      return $this->adapter->rows ($this->num);
    }
  }
