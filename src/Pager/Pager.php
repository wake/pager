<?php

  namespace Pager;


  /**
   *
   * Pager
   *
   */
  class Pager extends Core implements Contract\Adapter {


    /**
     *
     * Data
     *
     */
    protected $data = [];


    /**
     *
     * Config
     *
     */
    protected $config;


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
