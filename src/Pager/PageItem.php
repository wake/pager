<?php

  namespace Pager;


  /**
   *
   * Page item
   *
   */
  class PageItem {


    /**
     *
     * Pager
     *
     */
    var $pager;


    /**
     *
     * Num - Page num
     *
     */
    var $num;


    /**
     *
     * Active - Page if active
     *
     */
    var $active = false;


    /**
     *
     * Counstruct
     *
     */
    public function __construct ($pager, $num = null) {
      $this->pager = $pager;
      $this->num = is_null ($num) ? null : intval ($num);
    }


    /**
     *
     * Echo page number
     *
     */
    public function __toString () {
      return strval ($this->num);
    }


    /**
     *
     * Getting
     *
     */
    public function __get ($name) {

      switch ($name) {
        case 'val':
        case 'value':
          return $this->num;

        case 'url':
          return $this->url ();
      }
    }


    /**
     *
     * Echo page number
     *
     */
    public function num ($num) {
      $this->num = intval ($num);
      return $this;
    }


    /**
     *
     * Generate URL
     *
     */
    public function url () {
      return $this->pager->url ($this);
    }
  }
