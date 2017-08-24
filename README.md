# Pager

A lightweight PHP paginator.

## Installation

Add in your `composer.json` with following require entry:

```json
{
  "require": {
    "wake/pager": "*"
  }
}
```

or using composer:

```bash
$ composer require wake/pager:*
```

then run `composer install` or `composer update`.

## Usage: Pager

Use Pager without any ORM.

```php

Use Pager\Pager;

$pager = new Pager ();

$pager->total (1000) // 1000 data items
  ->page (13)        // current page is no.13
  ->paging ();

// first page: 1
echo $pager->pages->top;

// last page (total pages): 67
echo $pager->pages->end;

// first page of displayed pages: 8
echo $page->pages->first;

// last page of displayed pages: 17
echo $page->pages->last;

// pages
foreach ($pager->pages as $page)
  echo $page . "<br />";

// page url
foreach ($pager->pages as $page)
  echo $page->url . "<br />";

```

### `$pager->show ($show)`

Set amount of item displayed per page, default `15`.

```php
// Display 12 items on each page.
$pager->show (12);
```

### `$pager->total ($total)`

Set item total amount.

```php
// Set total amount of 100.
$pager->total (100);
```

### `$pager->size ($size)`

Set amount of page list per page, default `10`.

```php
// Display 5 page items on each page - `1 2 3 4 5`
$pager->size (5);
```

### `$pager->page ($page)`

Set current page, default `1`.

```php
// Set current page of No.3
$pager->page (3);
```

### `$pager->dynamic ()`, `$pager->fixed ()`

List page group dynamically or fixed, default is `dynamic`.

```php
// Set page items list dynamically: `1 2 3 4 5` -> `2 3 4 5 6` -> `3 4 5 6 7`
$pager->dynamic ();

// Set page items list fixed: `1 2 3 4 5` -> `6 7 8 9 10`
$pager->fixed ();
```

### `$pager->url ($pattern)`

Set url pattern or handling function, default is pattern `(:num)`.

```php
// /?page=3
$pager->url ('/?page=(:num)');

// /?p=3
$pager->url (function ($pageItem) {
  return '/?p=' . $pageItem->num;
});
```

### `$pager->paging ()`

Calculate and build page items, must be called after all options are set.

```php
$pager->paging ();
```

## Feedback

Please feel free to open an issue and let me know if there is any thoughts or questions :smiley:

## License

Released under the MIT license
