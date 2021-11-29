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
     * Slice data rows
     *
     */
    public function slice (Int $offset, Int $limit);
  }
