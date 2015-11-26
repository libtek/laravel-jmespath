# laravel-jmespath

A Laravel 5 wrapper for use of the [jmespath.php](https://github.com/jmespath/jmespath.php) library.  The jmespath.php library is an implementation of the [JMESPath](http://jmespath.org/) specification.

This package also provides Artisan commands to pre-compile and manage your JMESPath expressions.

## Installation

### Install through composer

```bash
$ composer require libtek/laravel-jmespath
```

### Add Service Provider

In `config/app.php`, add the service provider to the `$providers` array:

```php
'providers' => [
    // ...
    Libtek\Jmes\JmesServiceProvider::class,
],
```

### Add alias

In `config/app.php`, add the facade to the `$aliases` array:

```php
'aliases' => [
    // ...
    'Jmes' => Libtek\Jmes\Facades\Jmes::class,
],
```

### Register the commands

If you'd like to make use of the Artisan commands, in `app/Console/Kernel.php`, add them to the `$commands` array:

```php
protected $commands = [
    // ...
    \Libtek\Jmes\Console\JmesCompileCommand::class,
    \Libtek\Jmes\Console\JmesClearCommand::class,
];
```

### Publish the configuration file

If you'd like to modify the default configuration values or define expressions to pre-compile, publish the package config file:
```bash
php artisan vendor:publish --provider="Libtek\Jmes\JmesServiceProvider"
```
This will create a `jmes.php` file in your `config` directory.

## Usage

#### With facade:
```php
$result = Jmes::search($expression, $data);
```

#### Helper function:

```php
$result = jmes($expression, $data);
```

### Artisan commands

Two Artisan commands are available with the package:

#### `jmes:compile`

This compiles and caches JMESPath expressions.  Expressions can be sourced in multiple ways:

1. Running the command with no options or arguments will look for expressions in the `jmes.php` config file:

    ```bash
    php artisan jmes:compile
    ```

2. Passing a single expression to the command:

    ```bash
    php artisan jmes:compile 'foo.*.baz'
    ```

2. Setting the `-c` or `--cli` option will prompt for expressions to be added manually:

    ```bash
    php artisan jmes:compile --cli

     Please enter a JMESPath expression:
     > foo.*.baz
    ```

#### `jmes:clear`

This will delete any previously compiled expressions:

```bash
php artisan jmes:clear
```

Pass `-h` or `--help` to either command to view its usage.
