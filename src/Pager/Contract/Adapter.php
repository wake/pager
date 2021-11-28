<?php

  namespace Pager\Contract;



  /**
   *
   * Adapter interface
   *
   */
  interface Adapter {


    /**
     *
     * Get total amount of rows
     *
     */
    public function count ();


    /**
     *
     * Count rows of current page
     *
     */
    public function rows (Int $page);
  }
