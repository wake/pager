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

// first page of page group: 8
echo $page->pages->first;

// last page of page group: 17
echo $page->pages->last;

// pages
foreach ($pager->pages as $page)
  echo $page . "<br />";

// page url
foreach ($pager->pages as $page)
  echo $page->url . "<br />";

```

### `$pager->show ($show)`

Set amount of items per page, default to `15`.

```php
// Display 12 items on each page.
$pager->show (12);
```

### `$pager->total ($total)`

Set total amount of items.

```php
// Set total amount of 100.
$pager->total (100);
```

### `$pager->size ($size)`

Set amount of page list per page, default to `10`.

```php
// Display 5 pages of page group on each page - `1 2 3 4 5`
$pager->size (5);
```

### `$pager->page ($page)`

Set current page, default to `1`.

```php
// Set current to page 3
$pager->page (3);
```

### `$pager->dynamic ()`, `$pager->fixed ()`

List pages of page group dynamically or fixed, default is `dynamic`.

```php
// List dynamically: `1 2 3 4 5` -> `2 3 4 5 6` -> `3 4 5 6 7`
$pager->dynamic ();

// List fixed: `1 2 3 4 5` -> `6 7 8 9 10`
$pager->fixed ();
```

### `$pager->url ($pattern)`

Set url pattern or handler function, default is pattern `(:num)`.

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

## Usage: Pages

Container of page items.

```php
Use Pager\Pager;

$pager = new Pager ();

$pager->total (1000) // 1000 data items
  ->page (13)        // current page is no.13
  ->paging ();

$pages = $pager->pages;
```

### `$pages->top`

First page of all pages, generally is 1.

### `$pages->end`

Last page of all pages.

### `$pages->first`

First page of page group.

### `$pages->last`

First page of page group.

### `$pages->total`

Total amount of pages.

### `$pages->current`, `$pages->page`

Current page.

### `$pages->next`

Next page.

### `$pages->prev`

Previous page.

### `$pages->next*N`

Next `N` page.

```php
// Next page
$pages->next;
$pages->next1;

// Next 5 page
$pages->next5;
```

### `$pages->prev*N`

Previous `N` page.

```php
// Previous page
$pages->prev;
$pages->prev1;

// Previous 5 page
$pages->prev5;
```

## Usage: Page item

Page item.

```php
Use Pager\Pager;

$pager = new Pager ();

$pager->total (1000) // 1000 data items
  ->page (13)        // current page is no.13
  ->paging ();

$item = $pager->pages->first;
```

### `$item`, `$item->num`

Number of the page, $item could be use as string directly.

```php
echo $item;
echo $item->num;
```

### `$item->url ()`

Url of the page.

```php
echo $item->url ();
```

## Feedback

Please feel free to open an issue and let me know if there is any thoughts or questions :smiley:

## License

Released under the MIT license
