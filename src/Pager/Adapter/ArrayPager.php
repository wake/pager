<?php

  namespace Pager\Adapter;


  /**
   *
   * Pager
   *
   */
  class ArrayPager extends \Pager\Core implements \Pager\Contract\Adapter {


    /**
     *
     * Data
     *
     */
    protected $data = [];


    /**
     *
     * Construct
     *
     */
    function __construct ($data = []) {
      $this->data = $data;
      return parent::__construct ();
    }


    /**
     *
     * Count
     *
     */
    function count () {
      return count ($this->data);
    }


    /**
     *
     * Rows
     *
     */
    function rows ($page) {

      $limit = $this->limit;
      $offset = ($page - 1) * $limit;

      return array_slice ($this->data, $offset, $limit);
    }
  }
