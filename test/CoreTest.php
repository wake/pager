<?php

require 'vendor/autoload.php';

use Pager\Core;
use PHPUnit\Framework\TestCase;


// A simple core fo testing
class SimpleCore extends Core {

  // Data var
  public $data = [];

  // Exnteds count func
  public function count () {
    return count ($this->data);
  }
}


class CoreTest extends TestCase {

  // Stack
  private $core;

  // Setup
  protected function setUp (): void {
    $this->core = new SimpleCore ();
    $this->core->data = range (1, 512);
    $this->core->page (13);
  }

  // Teardown
  protected function tearDown (): void {
    $this->core = null;
  }

  //
  public function testInitial () : void {
    $core = new Core ();
    $this->assertIsObject ($core);
  }

  //
  public function testProxyIsset () : void {
    $core = $this->core;
    $this->assertEquals (isset ($core->pages->top), isset ($core->top));
    $this->assertEquals (isset ($core->pages->end), isset ($core->end));
    $this->assertEquals (isset ($core->pages->first), isset ($core->first));
    $this->assertEquals (isset ($core->pages->last), isset ($core->last));
    $this->assertEquals (isset ($core->pages->prev), isset ($core->prev));
    $this->assertEquals (isset ($core->pages->next), isset ($core->next));
    $this->assertEquals (isset ($core->pages->current), isset ($core->current));
    $this->assertEquals (isset ($core->pages->prev3), isset ($core->prev3));
    $this->assertEquals (isset ($core->pages->next3), isset ($core->next3));
    $this->assertEquals (isset ($core->pages->page6), isset ($core->page6));

    $core->paging ();
    $this->assertEquals (isset ($core->pages->top), isset ($core->top));
    $this->assertEquals (isset ($core->pages->end), isset ($core->end));
    $this->assertEquals (isset ($core->pages->first), isset ($core->first));
    $this->assertEquals (isset ($core->pages->last), isset ($core->last));
    $this->assertEquals (isset ($core->pages->prev), isset ($core->prev));
    $this->assertEquals (isset ($core->pages->next), isset ($core->next));
    $this->assertEquals (isset ($core->pages->current), isset ($core->current));
    $this->assertEquals (isset ($core->pages->prev3), isset ($core->prev3));
    $this->assertEquals (isset ($core->pages->next3), isset ($core->next3));
    $this->assertEquals (isset ($core->pages->page6), isset ($core->page6));
  }

  //
  public function testProxyGet () : void {
    $core = $this->core;
    $this->assertEquals ($core->pages->top, $core->top);
    $this->assertEquals ($core->pages->end, $core->end);
    $this->assertEquals ($core->pages->first, $core->first);
    $this->assertEquals ($core->pages->last, $core->last);
    $this->assertEquals ($core->pages->prev, $core->prev);
    $this->assertEquals ($core->pages->next, $core->next);
    $this->assertEquals ($core->pages->current, $core->current);
    $this->assertEquals ($core->pages->prev3, $core->prev3);
    $this->assertEquals ($core->pages->next3, $core->next3);
    $this->assertEquals ($core->pages->page6, $core->page6);

    $core->paging ();
    $this->assertEquals ($core->pages->top, $core->top);
    $this->assertEquals ($core->pages->end, $core->end);
    $this->assertEquals ($core->pages->first, $core->first);
    $this->assertEquals ($core->pages->last, $core->last);
    $this->assertEquals ($core->pages->prev, $core->prev);
    $this->assertEquals ($core->pages->next, $core->next);
    $this->assertEquals ($core->pages->current, $core->current);
    $this->assertEquals ($core->pages->prev3, $core->prev3);
    $this->assertEquals ($core->pages->next3, $core->next3);
    $this->assertEquals ($core->pages->page6, $core->page6);
  }

  //
  public function testBasicPaging () : void {
    $core = $this->core;
    $core->paging ();

    $this->assertEquals ((String) $core->pages->top,   1);
    $this->assertEquals ((String) $core->pages->end,   35);

    $core->limit (12)->paging ();
    $this->assertEquals ((String) $core->pages->top,   1);
    $this->assertEquals ((String) $core->pages->end,   43);
  }

  //
  public function testDynamicPaging () : void {
    $core = $this->core;
    $core->dynamic ()->paging ();

    $core->page (1)->paging ();
    $this->assertEquals ((String) $core->pages->first,  1);
    $this->assertEquals ((String) $core->pages->last,   10);

    $core->page (3)->paging ();
    $this->assertEquals ((String) $core->pages->first,  1);
    $this->assertEquals ((String) $core->pages->last,   10);

    $core->page (6)->paging ();
    $this->assertEquals ((String) $core->pages->first,   1);
    $this->assertEquals ((String) $core->pages->last,   10);

    $core->page (7)->paging ();
    $this->assertEquals ((String) $core->pages->first,   2);
    $this->assertEquals ((String) $core->pages->last,   11);

    $core->page (13)->paging ();
    $this->assertEquals ((String) $core->pages->first,   8);
    $this->assertEquals ((String) $core->pages->last,   17);

    $core->page (35)->paging ();
    $this->assertEquals ((String) $core->pages->first,  26);
    $this->assertEquals ((String) $core->pages->last,   35);
  }

  //
  public function testFixedPaging () : void {
    $core = $this->core;
    $core->fixed ()->paging ();

    $core->page (1)->paging ();
    $this->assertEquals ((String) $core->pages->first,  1);
    $this->assertEquals ((String) $core->pages->last,   10);

    $core->page (7)->paging ();
    $this->assertEquals ((String) $core->pages->first,   1);
    $this->assertEquals ((String) $core->pages->last,   10);

    $core->page (10)->paging ();
    $this->assertEquals ((String) $core->pages->first,  1);
    $this->assertEquals ((String) $core->pages->last,   10);

    $core->page (13)->paging ();
    $this->assertEquals ((String) $core->pages->first,  11);
    $this->assertEquals ((String) $core->pages->last,   20);

    $core->page (35)->paging ();
    $this->assertEquals ((String) $core->pages->first,  26);
    $this->assertEquals ((String) $core->pages->last,   35);
  }
}
