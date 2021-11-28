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
     * Get total amount of items
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
