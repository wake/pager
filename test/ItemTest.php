<?php

require 'vendor/autoload.php';

use Pager\Item;
use Pager\Config;
use PHPUnit\Framework\TestCase;


class ItemTest extends TestCase {

  // Stack
  private $item;
  private $config;

  // Setup
  protected function setUp (): void {
    $this->config = new Config ();
    $this->item = new Item ($this->config, 0);
  }

  // Teardown
  protected function tearDown (): void {
    $this->item = null;
    $this->config = null;
  }

  //
  public function testInitial () : void {

    $item = new Item ($this->config);
    $this->assertIsObject ($item);
    $this->assertEquals ($item->num, null);

    $item = new Item ($this->config, 13);
    $this->assertEquals ($item->num, 13);
  }

  //
  public function testNum () : void {

    $item = $this->item;

    $item->val = 15;
    $this->assertEquals ($item->num, 15);

    $item->value = 16;
    $this->assertEquals ($item->num, 16);

    $item->num = 17;
    $this->assertEquals ($item->num, 17);
    $this->assertEquals ($item->val, 17);
    $this->assertEquals ($item->value, 17);
  }

  //
  public function testActive () : void {

    $item = $this->item;

    $item->enable = true;
    $this->assertTrue ($item->enable);
    $this->assertTrue ($item->active);
    $this->assertTrue ($item->isActive);
    $this->assertTrue ($item->isCurrent);

    $item->active = false;
    $this->assertFalse ($item->enable);
    $this->assertFalse ($item->active);
    $this->assertFalse ($item->isActive);
    $this->assertFalse ($item->isCurrent);

    $item->current = true;
    $this->assertTrue ($item->enable);
    $this->assertTrue ($item->active);
    $this->assertTrue ($item->isActive);
    $this->assertTrue ($item->isCurrent);
  }

  //
  public function testToString () : void {

    $item = $this->item;

    $item->val = 22;
    $this->assertEquals ((String) $item, '22');
  }

  //
  public function testInvoke () : void {

    $item = $this->item;

    $item->val = 36;
    $this->assertEquals ($item (), '36');
  }

  //
  public function testUrl () : void {

    $item = $this->item;
    $config = $this->config;

    $item->num = 7;
    $config->url ('https://pattern-test.com?page=(:num)');

    $this->assertEquals ($item->url (), 'https://pattern-test.com?page=7');

    $item->num = 27;
    $config->url (function ($p) {
      return "https://func-test.com?page=" . $p;
    });

    $this->assertEquals ($item->url (), 'https://func-test.com?page=27');
  }
}
