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
      'count' => null,

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
    public function __construct ($orm = null) {

      parent::__construct ();
      $this->orm = $orm;

      return $this;
    }


    /**
     *
     * Make Pager as `Paris Model` wrapper
     *
     */
    public static function factory ($table) {

      $pager = new ParisPager (Model::factory ($table));
      $pager->odata['table'] = $table;

      return $pager;
    }


    /**
     *
     * Count data amount
     *
     */
    public function count ($handler = null) {

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
     * Slice data
     *
     */
    public function slice ($offset, $limit) {

      // Split data
      return $this->orm = $this->orm
        ->offset ($offset)
        ->limit ($limit)
        ->find_array ();
    }


    /**
     *
     * Interception functions for ORM class
     *
     */
    public function __call ($func, $args) {

      if (isset ($this->$func) && is_callable ($call = $this->$func))
        return call_user_func_array ($call, $args);

      // 優先確認是否呼叫 pager 自身相關函式
      if (method_exists ($this, $func))
        return call_user_func_array ([$this, $func], $args);

      // 是否呼叫 paris 的 find_many / find_array
      if ($func == 'find_many' || $func == 'find_array') {

        // Counting
        if (is_null ($this->odata['total']))
          $this->odata['total'] = $this->count ();

        // Paging
        $this->paging ();

        $page = $this->current->num;
        $limit = $this->_setting['row']['limit'];
        $offset = $this->_setting['row']['offset'];

        // Split data
        $this->orm = $this->orm
          ->offset ((max ($page - 1, 0) * $limit) + $offset)
          ->limit ($limit);

        return call_user_func_array ([$this->orm, $func], $args);
      }

      // 呼叫 orm 的其他函式
      $this->orm = call_user_func_array ([$this->orm, $func], $args);

      return $this;
    }
  }
