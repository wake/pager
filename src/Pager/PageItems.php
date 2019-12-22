<?php

  namespace Pager;


  /**
   *
   * Page items
   *
   */
  class PageItems implements \Iterator, \ArrayAccess, \Countable {


    /**
     *
     * Pager
     *
     */
    var $pager;


    /**
     *
     * Items - Page items
     *
     */
    protected $data = [
      'top'   => null,
      'end'   => null,
      'items' => null,
    ];


    /**
     *
     * Pos - Index position
     *
     */
    protected $pos = 0;


    /**
     *
     * Top - First page of all pages
     *
     */
    protected $_top = 1;


    /**
     *
     * End - Last page of all pages
     *
     */
    protected $_end = 1;


    /**
     *
     * First - First page of current pages
     *
     */
    protected $_first = 1;


    /**
     *
     * Last - Last page of current pages
     *
     */
    protected $_last = 1;


    /**
     *
     * Prev - Previous page
     *
     */
    protected $_prev = 1;


    /**
     *
     * Next - Next page
     *
     */
    protected $_next = 1;


    /**
     *
     * Current - Current page
     *
     */
    protected $_current = 1;


    /**
     *
     * Total - Page amount
     *
     */
    protected $_total = 1;


    /**
     *
     * Position of current page
     *
     */
    protected $cpos = null;


    /**
     *
     * Counstruct
     *
     */
    public function __construct ($pager) {
      $this->pager = $pager;
    }


    /**
     *
     * Setting
     *
     */
    public function __set ($name, $value) {

      $_name = "_$name";

      if (isset ($this->$_name))
        $this->$_name = $value;

      else
        $this->$name = $name;

      return $this;
    }


    /**
     *
     * Getting
     *
     */
    public function __get ($name) {

      switch ($name) {

        case 'total':
          $name = 'end';

        case 'top':
        case 'end':
          return $this->data[$name];

        case 'first':
          return $this->data['items'][0];

        case 'last':
          return end ($this->data['items']);

        case 'prev':
        case 'prev1':

          if (is_null ($this->cpos))
            return new PageItem ($this->pager, $this->_prev);

          return $this->data['items'][max ($this->cpos - 1, 0)];

        case 'next':
        case 'next1':

          if (is_null ($this->cpos))
            return new PageItem ($this->pager, $this->_next);

          return $this->data['items'][min ($this->cpos + 1, count ($this->data['items']) - 1)];

        case 'page':
        case 'current':

          if (is_null ($this->cpos))
            return new PageItem ($this->pager, $this->_current);

          return $this->data['items'][$this->cpos];

        case 'isTop':
          return $this->_current == $this->_top;

        case 'isEnd':
          return $this->_current == $this->_end;

        case 'isFirst':
          return $this->_current == $this->_first;

        case 'isLast':
          return $this->_current == $this->_last;
      }

      $operate = substr ($name, 0, 4);

      if (in_array ($operate, ['page', 'next', 'prev']) && strlen ($name) > 4) {

        $operate = $operate . 'N';

        return $this->$operate (substr ($name, 4));
      }
    }


    /**
     *
     * Jump to page N
     *
     */
    public function pageN ($page) {

      if ($page < 1)
        $page = 1;

      else if ($page > $this->_end)
        $page = $this->_end;

      return new PageItem ($this->pager, $page);
    }


    /**
     *
     * Jump to next N
     *
     */
    public function nextN ($next = 'N') {

      $page = $this->_current;

      if ($next == 'N')
        $page += $this->pager->setting['page']['size'];

      else
        $page += intval ($next);

      return $this->pageN ($page);
    }


    /**
     *
     * Jump to previous N
     *
     */
    public function prevN ($prev = 'N') {

      $page = $this->_current;

      if ($prev == 'N')
        $page -= $this->pager->setting['page']['size'];

      else
        $page -= intval ($prev);

      return $this->pageN ($page);
    }


    /**
     *
     * Build page items
     *
     */
    public function build () {

      // Reset items;
      $this->data['items'] = [];

      for ($i=$this->_first; $i<=$this->_last; $i++) {

        $this->data['items'][] = new PageItem ($this->pager, $i);

        if ($i == $this->_current)
          $this->cpos = $i - $this->_first;
      }

      $this->data['top'] = new PageItem ($this->pager, $this->_top);
      $this->data['end'] = new PageItem ($this->pager, $this->_end);

      // Current page
      $this->data['items'][$this->cpos]->active = true;

      if ($this->_top == $this->_current)
        $this->data['top']->active = true;

      if ($this->_end == $this->_current)
        $this->data['end']->active = true;

      return $this;
    }


    // Iterator: rewind
    public function rewind () {
      $this->pos = 0;
    }

    // Iterator: current
    public function current () {
      return $this->data['items'][$this->pos];
    }

    // Iterator: key
    public function key () {
      return $this->pos;
    }

    // Iterator: next
    public function next () {
      ++$this->pos;
    }

    // Iterator: valid
    public function valid () {
      return isset ($this->data['items'][$this->pos]);
    }

    // ArrayAccess: offsetSet
    public function offsetSet ($offset, $value) {
      // Can't set
    }

    // ArrayAccess: offsetExists
    public function offsetExists ($offset) {

      if (in_array ($offset, ['top', 'end', 'first', 'last', 'prev', 'next', 'current']))
        return true;

      else if (in_array ($offset, ['isTop', 'isEnd', 'isFirst', 'isLast']))
        return true;

      else if (in_array (substr ($offset, 0, 4), ['page', 'next', 'prev']))
        return true;

      return isset ($this->data['items'][$offset]);
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

      return isset ($this->data['items'][$offset]) ? $this->data['items'][$offset] : null;
    }

    // Countable: count
    public function count () {
      return count ($this->data['items']);
    }
  }
