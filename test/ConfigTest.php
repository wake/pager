<?php

require 'vendor/autoload.php';

use Pager\Config;
use PHPUnit\Framework\TestCase;


class ConfigTest extends TestCase {

  // Stack
  private $config;

  // Setup
  protected function setUp (): void {
    $this->config = new Config ();
  }

  // Teardown
  protected function tearDown (): void {
    $this->config = null;
  }

  //
  public function testInitial () : void {
    $config = new Config ();
    $this->assertIsObject ($config);
  }

  //
  public function testOffset () : void {

    $config = $this->config;

    // Isset
    $this->assertTrue (isset ($config->offset));

    // Set
    $config->offset = 99;
    $this->assertEquals ($config->offset, 99);

    // Set
    $config->offset (101);
    $this->assertEquals ($config->offset, 101);
  }

  //
  public function testLimit () : void {

    $config = $this->config;

    // Isset
    $this->assertTrue (isset ($config->limit));

    // Set
    $config->limit = 99;
    $this->assertEquals ($config->limit, 99);

    // Set
    $config->limit (101);
    $this->assertEquals ($config->limit, 101);
  }

  //
  public function testSize () : void {

    $config = $this->config;

    // Isset
    $this->assertTrue (isset ($config->size));

    // Set
    $config->size = 99;
    $this->assertEquals ($config->size, 99);

    // Set
    $config->size (101);
    $this->assertEquals ($config->size, 101);
  }

  //
  public function testDynamic () : void {

    $config = $this->config;

    // Isset
    $this->assertTrue (isset ($config->dynamic));

    // Set
    $config->dynamic = false;
    $this->assertEquals ($config->dynamic, false);
    $this->assertEquals ($config->fixed, true);

    // Set
    $config->dynamic = true;
    $this->assertEquals ($config->dynamic, true);
    $this->assertEquals ($config->fixed, false);

    // Set
    $config->dynamic (false);
    $this->assertEquals ($config->dynamic, false);
    $this->assertEquals ($config->fixed, true);

    // Set
    $config->dynamic ();
    $this->assertEquals ($config->dynamic, true);
    $this->assertEquals ($config->fixed, false);
  }

  //
  public function testFixed () : void {

    $config = $this->config;

    // Isset
    $this->assertTrue (isset ($config->fixed));

    // Set
    $config->fixed = false;
    $this->assertEquals ($config->fixed, false);
    $this->assertEquals ($config->dynamic, true);

    // Set
    $config->fixed = true;
    $this->assertEquals ($config->fixed, true);
    $this->assertEquals ($config->dynamic, false);

    // Set
    $config->fixed (false);
    $this->assertEquals ($config->fixed, false);
    $this->assertEquals ($config->dynamic, true);

    // Set
    $config->fixed ();
    $this->assertEquals ($config->fixed, true);
    $this->assertEquals ($config->dynamic, false);
  }

  //
  public function testUrl () : void {

    $config = $this->config;

    // Set url as pattern type
    $config->url ('https://pattern-test.com?page=(:num)');
    $this->assertEquals ($config->url (3), 'https://pattern-test.com?page=3');

    // Set url as function type
    $config->url (function ($p) {
      return "https://func-test.com?page=" . $p;
    });

    $this->assertEquals ($config->url (9), 'https://func-test.com?page=9');
  }
}
