<?php

  namespace Pager\Adapter;

  use Model;


  /**
   *
   * Pager Wrapper for Paris ORM
   *
   */
  class ParisPager extends \Pager\Core implements \Pager\Contract\Adapter {


    /**
     *
     * ORM
     *
     */
    protected $orm = null;


    /**
     *
     * ORM datas
     *
     */
    protected $odata = [

      // Count handler
      'count' => null

      // ORM table
      'table' => null,

      // Total
      'total' => null,
    ];


    /**
     *
     * Construct
     *
     */
    function __construct ($orm = null) {

      parent::__construct ();
      $this->orm = $orm;

      return $this;
    }


    /**
     *
     * Make Pager as `Paris Model` wrapper
     *
     */
    static function factory ($table) {

      $pager = new ParisPager (Model::factory ($table));
      $pager->odata['table'] = $table;

      return $pager;
    }


    /**
     *
     * Count item amount
     *
     */
    function count ($handler = null) {

      // Assign count handler (query or function)
      if (! is_null ($handler)) {
        $this->odata['count'] = $handler;
        $this->odata['total'] = null;
      }

      // Do counting
      else {

        if (! is_null ($this->odata['total']))
          return $this->odata['total'];

        $handler = $this->odata['count'];

        // No handler, execute $orm->count () as default
        if (is_null ($handler))
          return $this->orm->count ();

        // Handler with raw query string
        if (is_string ($handler)) {

          $raw = $handler;

          $model = Model::Factory ($this->odata['table']);
          $model->raw_query ($raw);
          $res = $model->find_array ();

          return current ($res[0]);
        }

        // Handler with custome function
        if (is_callable ($handler)) {
          $model = Model::Factory ($this->odata['table']);
          return $handler ($model);
        }
      }

      return $this;
    }


    /**
     *
     * Interception functions for ORM class
     *
     */
    public function __call ($func, $args) {

      if (function_exists ($this, $func))
        return call_user_func_array ([$this, $func], $args);

      if ($func == 'find_many' || $func == 'find_array') {

        // Counting
        if (is_null ($this->odata['total']))
          $this->odata['total'] = $this->count ();

        // Paging
        $this->paging ();

        $page = $this->current->num;
        $limit = $this->_setting['item']['limit'];
        $offset = $this->_setting['item']['offset'];

        // Split data
        $this->orm = $this->orm
          ->offset ((($page - 1) * $limit) + $offset)
          ->limit ($limit);

        return call_user_func_array ([$this->orm, $func], $args);
      }

      $this->orm = call_user_func_array ([$this->orm, $func], $args);

      return $this;
    }


    /**
     *
     * Rows
     *
     */
    public function rows ($page) {

      $limit = $this->_setting['item']['limit'];
      $offset = $this->_setting['item']['offset'];

      // Split data
      $this->orm = $this->orm
        ->offset ((($page - 1) * $limit) + $offset)
        ->limit ($limit);

      return $this->orm->find_array ();
    }
  }