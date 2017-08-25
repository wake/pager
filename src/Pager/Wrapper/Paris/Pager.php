<?php

  namespace Pager\Wrapper\Paris;


  use Pager\Pager as PagerBasic;
  use Model;


  /**
   *
   * Pager Wrapper for Paris ORM
   *
   */
  class Pager extends PagerBasic {


    /**
     *
     * ORM
     *
     */
    var $orm = null;


    /**
     *
     * ORM datas
     *
     */
    var $odata = [

      // ORM queries
      'query' => [],

      // ORM table
      'table' => null,
    ];


    /**
     *
     * Query
     *
     */
    var $query = [];


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

      $pager = new Pager (Model::factory ($table));
      $pager->odata['table'] = $table;

      return $pager;
    }


    /**
     *
     * Count item amount
     *
     */
    function count ($query = '') {

      // Assign count handler (query or function)
      if ($query != '')
        $this->odata['query']['count'] = $query;

      // Do counting
      else {

        if (isset ($this->odata['query']['count'])) {

          // Query string handler
          if (is_string ($this->odata['query']['count'])) {

            $raw = $this->odata['query']['count'];

            $model = Model::Factory ($this->odata['table']);
            $model->raw_query ($raw);
            $res = $model->find_array ();

            return current ($res[0]);
          }

          // Function handler
          else if (is_callable ($this->odata['query']['count'])) {
            $model = Model::Factory ($this->odata['table']);
            $func = $this->odata['query']['count'];
            return $func ($model);
          }
        }

        // Native counting handler
        else {
          return $this->orm->count ();
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

      if ($func == 'find_many' || $func == 'find_array') {

        // Counting
        if (is_null ($this->setting['item']['total']))
          $this->setting['item']['total'] = $this->count ();

        // Paging
        $this->paging ();

        $page = $this->pages->current->num;
        $show = $this->setting['item']['show'];

        // Split data
        $this->orm = $this->orm
          ->offset (($page - 1) * $show)
          ->limit ($show);

        return call_user_func_array ([$this->orm, $func], $args);
      }

      $this->orm = call_user_func_array ([$this->orm, $func], $args);

      return $this;
    }
  }
