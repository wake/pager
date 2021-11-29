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
    public function __construct ($data = []) {
      $this->data = $data;
      return parent::__construct ();
    }


    /**
     *
     * Count data
     *
     */
    public function count () {
      return count ($this->data);
    }


    /**
     *
     * Slice data
     *
     */
    public function slice ($offset, $limit) {
      return array_slice ($this->data, $offset, $limit);
    }
  }
