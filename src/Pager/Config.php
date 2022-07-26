<?php

  namespace Pager;


  /**
   *
   * Config
   *
   */
  class Config {


    /**
     *
     * Setting
     *
     */
    protected $_setting = [

      'row' => [

        // offset - The offset of the first row in collection
        'offset' => 0,

        // show - How many items list per page
        'limit' => 15,

        // amount - Amount of items
        'amount' => null,
      ],

      'page' => [

        // size - How many pages display once
        'size' => 10,

        // dynamic - Display pagination numbers dynamical or fixed
        'dynamic' => true,

        // current - Current page
        '_current' => 1,
      ],

      'url' => [

        // type of generator
        'type' => 'pattern',

        // pattern with `page` parameter in query
        'pattern' => '(:num)',

        // function
        'func' => null,

        // url string
        'string' => null,
      ],
    ];


    /**
     *
     * Construct
     *
     */
    function __construct () {
    }


    /**
     *
     * Isset
     *
     */
    public function __isset ($name) {

      if (in_array ($name, ['offset', 'limit', 'amount', 'size', 'dynamic', 'fixed']))
        return true;

      $vars = array_keys (get_class_vars (static::class));

      return in_array ($name, $vars);
    }


    /**
     *
     * Short access
     *
     */
    public function __get ($name) {

      switch ($name) {

        // Ref to row setting
        case 'offset':
          return $this->_setting['row']['offset'];
          break;

        case 'limit':
          return $this->_setting['row']['limit'];
          break;

        case 'amount':
          return $this->_setting['row']['amount'];
          break;

        // Ref to page setting
        case 'size':
          return $this->_setting['page']['size'];
          break;

        case 'dynamic':
          return $this->_setting['page']['dynamic'];
          break;

        case 'fixed':
          return ! $this->_setting['page']['dynamic'];
          break;
      }

      return $this->$name;
    }


    /**
     *
     * Short access
     *
     */
    public function __set ($name, $value) {

      switch ($name) {

        // Ref to row settings
        case 'offset':
          return $this->offset ($value);
          break;

        case 'limit':
          return $this->limit ($value);
          break;

        // Not allow setting amount of row

        // Ref to page setting
        case 'size':
          return $this->size ($value);
          break;

        case 'dynamic':
          return $this->dynamic ($value);
          break;

        case 'fixed':
          return $this->fixed ($value);
          break;
      }

      $this->$name = $value;
    }


    /**
     *
     * Set row offset
     *
     */
    public function offset ($offset = 0) {
      $this->_setting['row']['offset'] = $offset < 0 ? 0 : $offset;
      return $this;
    }


    /**
     *
     * Set row limit
     *
     */
    public function limit ($limit) {
      $this->_setting['row']['limit'] = $limit < 1 ? 1 : $limit;
      return $this;
    }


    /**
     *
     * Set row amount (not public)
     *
     */
    protected function amount ($amount) {
      $this->_setting['row']['amount'] = $amount < 0 ? 0 : $amount;
      return $this;
    }


    /**
     *
     * Set page group size
     *
     */
    public function size ($size) {
      $this->_setting['page']['size'] = $size < 1 ? 1 : $size;
      return $this;
    }


    /**
     *
     * Set current page
     *
     */
    public function page ($page) {
      $this->_setting['page']['_current'] = $page;
      return $this;
    }


    /**
     *
     * Set page items in group displayed dynamical or fixed
     *
     */
    public function dynamic ($dynamic = true) {
      $this->_setting['page']['dynamic'] = $dynamic === true;
      return $this;
    }


    /**
     *
     * Set page items in group displayed dynamical or fixed
     *
     */
    public function fixed ($fixed = true) {
      $this->_setting['page']['dynamic'] = $fixed !== true;
      return $this;
    }


    /**
     *
     * Url handler
     *
     */
    public function url ($para) {

      $p = false;

      /**
       *
       * Set URL generate rule
       *
       */
      if (is_string ($para)) {
        $this->_setting['url']['type'] = 'pattern';
        $this->_setting['url']['string'] = $para;
      }

      else if (is_callable ($para)) {
        $this->_setting['url']['type'] = 'func';
        $this->_setting['url']['func'] = $para;
      }

      /**
       *
       * Generate url by rule
       *
       */
      else if (is_numeric ($para))
        $p = intval ($para);

      else if (is_object ($para) && isset ($para->num))
        $p = $para->num;

      else if (is_null ($para))
        return null;

      if ($p !== false) {

        if ($this->_setting['url']['type'] == 'pattern') {
          $search = [$this->_setting['url']['pattern'], rawurlencode ($this->_setting['url']['pattern'])];
          $string = $this->_setting['url']['string'];
          return str_replace ($search, $p, $string);
        }

        else if ($this->_setting['url']['type'] == 'func') {
          $func = $this->_setting['url']['func'];
          return $func ($p);
        }
      }

      return $this;
    }
  }
