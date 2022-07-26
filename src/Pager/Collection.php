<?php

  namespace Pager;


  /**
   *
   * Page collection
   *
   */
  class Collection implements \Iterator, \ArrayAccess, \Countable {


    /**
     *
     * Adapter
     *
     */
    protected $adapter;


    /**
     *
     * Page cache
     *
     */
    protected $cache = [];


    /**
     *
     * Each state of page
     *
     */
    protected $page = [
      'pos'     => 0,
      'top'     => 1,
      'end'     => 1,
      'current' => 1,
      'group'   => [1],
    ];


    /**
     *
     * Construct
     *
     */
    public function __construct ($adapter, $page = []) {
      $this->adapter = $adapter;
      $this->paging ($page);
      return $this;
    }


    /**
     *
     * Isset
     *
     */
    public function __isset ($name) {

      if (in_array ($name, ['total', 'page', 'current', 'group'
        , 'top', 'end', 'first', 'last', 'prev', 'next'
        , 'isTop', 'isEnd', 'isFirst', 'isLast']))
        return true;

      $operate = substr ($name, 0, 4);

      if (in_array ($operate, ['page', 'next', 'prev']) && strlen ($name) > 4) {
        $page = intval (substr ($name, 4));
        return $page >= $this->page['top'] && $page <= $this->page['end'];
      }

      return false;
    }


    /**
     *
     * Isset
     *
     */
    public function __invoke () {
      return $this->page['group'];
    }


    /**
     *
     * Isset
     *
     */
    public function __call ($name, $args = []) {

      if (! in_array ($name, ['total', 'page', 'current', 'group'
        , 'top', 'end', 'first', 'last', 'prev', 'next']))
        return false;

      $item = $this->__get ($name);

      return $item ();
    }


    /**
     *
     * Getting
     *
     */
    public function __get ($name) {

      $p = false;

      switch ($name) {

        case 'page':
        case 'current':
          $p = $this->page['current'];
          break;

        case 'top':
        case 'end':
          $p = $this->page[$name];
          break;

        // Get by calculate
        case 'first':
          reset ($this->page['group']);
          $p = current ($this->page['group']);
          break;

        case 'last':
          $p = end ($this->page['group']);
          break;

        case 'prev':
        case 'prev1':
          $p = max ($this->page['current'] - 1, $this->page['top']);
          break;

        case 'next':
        case 'next1':
          $p = min ($this->page['current'] + 1, $this->page['end']);
          break;

        case 'total':
          return $this->page['end'];

        case 'group':
          return $this->page['group'];

        case 'isTop':
          return $this->page['current'] == $this->page['top'];

        case 'isEnd':
          return $this->page['current'] == $this->page['end'];

        case 'isFirst':
          reset ($this->page['group']);
          return $this->page['current'] == current ($this->page['group']);

        case 'isLast':
          return $this->page['current'] == end ($this->page['group']);
      }

      $operate = substr ($name, 0, 4);

      if (in_array ($operate, ['page', 'next', 'prev']) && strlen ($name) > 4) {

        $operate = $operate . 'N';

        $p = $this->$operate (intval (substr ($name, 4)));
      }

      if ($p !== false) {

        if (isset ($this->cache[$p]))
          return $this->cache[$p];

        if ($p >= $this->page['top'] && $p <= $this->page['end'])
          return $this->cache[$p] = new Item ($this->adapter, $p > 0 ? $p : null);
      }

      return null;
    }


    /**
     *
     * Set paging
     *
     */
    public function paging ($page) {

      foreach ($this->page as $k => $v) {
        $this->page[$k] = isset ($page[$k]) ? $page[$k] : $v;
      }

      return $this;
    }


    /**
     *
     * Reset
     *
     */
    public function reset () {

      $this->page = [
        'pos'     => 0,
        'top'     => 1,
        'end'     => 1,
        'current' => 1,
        'group'   => [1],
      ];

      $this->cache = [];

      return $this;
    }


    /**
     *
     * Jump to page N
     *
     */
    public function pageN ($page) {
      return $page;
    }


    /**
     *
     * Jump to next N
     *
     */
    public function nextN ($next = 'N') {

      $page = $this->page['current'];

      if ($next == 'N')
        $page += $this->adapter->size;

      else
        $page += intval ($next);

      return $page;
    }


    /**
     *
     * Jump to previous N
     *
     */
    public function prevN ($prev = 'N') {

      $page = $this->page['current'];

      if ($prev == 'N')
        $page -= $this->adapter->size;

      else
        $page -= intval ($prev);

      return $page;
    }


    /**
     *
     * Iterators
     *
     */

    // Iterator: rewind
    public function rewind () {
      $this->page['pos'] = 0;
    }

    // Iterator: current
    public function current () {
      return $this->page['group'][$this->page['pos']];
    }

    // Iterator: key
    public function key () {
      return $this->page['pos'];
    }

    // Iterator: next
    public function next () {
      ++$this->page['pos'];
    }

    // Iterator: valid
    public function valid () {
      return isset ($this->page['group'][$this->page['pos']]);
    }

    // ArrayAccess: offsetExists
    public function offsetExists ($offset) {

      if (in_array ($offset, ['top', 'end', 'first', 'last', 'prev', 'next', 'current']))
        return true;

      else if (in_array ($offset, ['isTop', 'isEnd', 'isFirst', 'isLast']))
        return true;

      else if (in_array (substr ($offset, 0, 4), ['page', 'next', 'prev']))
        return true;

      return isset ($this->page['group'][$offset]);
    }

    // ArrayAccess: offsetSet
    public function offsetSet ($offset, $value) {
      // Can't set
    }

    // ArrayAccess: offsetUnset
    public function offsetUnset ($offset) {
      // Can't unset
    }

    // ArrayAccess: offsetGet
    public function offsetGet ($offset) {

      if (in_array ($offset, ['top', 'end', 'first', 'last', 'prev', 'next', 'current']))
        return $this->$offset;

      else if (in_array ($offset, ['isTop', 'isEnd', 'isFirst', 'isLast']))
        return $this->$offset;

      else if (in_array (substr ($offset, 0, 4), ['page', 'next', 'prev']))
        return $this->$offset;

      return isset ($this->page['group'][$offset]) ? $this->page['group'][$offset] : null;
    }

    // Countable: count
    public function count () {
      return count ($this->page['group']);
    }
  }
