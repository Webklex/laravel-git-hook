# Git hook deployment made for Laravel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Via Composer

``` bash
$ composer require webklex/laravel-git-hook
```

## Setup

Add the service provider to the providers array in `config/app.php`.

``` php
'providers' => [
    Webklex\GitHook\Providers\LaravelServiceProvider::class,
];
```

## Publishing

You can publish everything at once

``` php
php artisan vendor:publish --provider="Webklex\GitHook\Providers\LaravelServiceProvider"
```

or you can publish groups individually.

``` php
php artisan vendor:publish --provider="Webklex\GitHook\Providers\LaravelServiceProvider" --tag="config"
```

## Usage

This library is designed to handle the automatic deployment by git hooks 
as simple as possible. There isn't much todo to get started: just add the
Provider and edit the `config/git-hook.php` file to make it fit your needs.


Custom configuration can be made within the config/git-hook.php file:
```
/*
|--------------------------------------------------------------------------
| Email recipients
|--------------------------------------------------------------------------
|
| The email address and name that notification emails will be sent to.
| Leave the array empty to disable emails.
|
| [
|     ['name' => 'Admin', 'address' => 'email@example.com'],
|     ...
| ]
|
*/
'email_recipients' => [],
 
 
/*
|--------------------------------------------------------------------------
| Email sender
|--------------------------------------------------------------------------
|
| The email address and name that notification emails will be sent from.
| This will default to the sender in config(mail.from) if left null.
|
*/
'email_sender' => ['address' => null, 'name' => null],
 
 
/*
|--------------------------------------------------------------------------
| Repository path
|--------------------------------------------------------------------------
|
| This the root path of the Git repository that will be pulled. If this
| is left empty the script will try to determine the directory itself
| but looking for the project's .env file it's nearby .git directory.
|
| No trailing slash
|
*/
'repo_path' => '',
 
 
/*
|--------------------------------------------------------------------------
| Allowed sources
|--------------------------------------------------------------------------
|
| A request will be ignored unless it comes from an IP listed in this
| array. Leave the array empty to allow all sources.
|
| This is useful for a little extra security if you run your own Git
| repo server.
|
*/
'allowed_sources' => [],
 
 
/*
|--------------------------------------------------------------------------
| Remote name
|--------------------------------------------------------------------------
|
| The name of the remote repository to pull the changes from
|
*/
'remote' => 'origin',
 
 
/*
|--------------------------------------------------------------------------
| Git binary path
|--------------------------------------------------------------------------
|
| The full path to the system git binary. e.g. /usr/bin/git
|
| Leave blank to let the system detect using the current PATH variable
|
*/
'git_path' => '',
 
 
/*
|--------------------------------------------------------------------------
| Logger file name
|--------------------------------------------------------------------------
|
| The filename of the logfile which will be used to store deployment
| information.
|
| By default it will use: git-hook
|
| The log file will be placed within the storage/log/ directory.
|
*/
'logfile' => 'git-hook',
 
 
/*
|--------------------------------------------------------------------------
| Url parameter
|--------------------------------------------------------------------------
|
| Please specify a url parameter. The router will adapt to it automatically.
|
| Example: if you enter 'another-git-hook'
|          It will be transformed into: https://your-domain.tld/another-git-hook
|
*/
'url' => 'git-hook'
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email github@webklex.com instead of using the issue tracker.

## Credits

- [Webklex][link-author]
- All Contributors

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/Webklex/laravel-git-hook.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Webklex/laravel-git-hook/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/Webklex/laravel-git-hook.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Webklex/laravel-git-hook.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/Webklex/laravel-git-hook.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/Webklex/laravel-git-hook
[link-travis]: https://travis-ci.org/Webklex/laravel-git-hook
[link-scrutinizer]: https://scrutinizer-ci.com/g/Webklex/laravel-git-hook/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Webklex/laravel-git-hook
[link-downloads]: https://packagist.org/packages/Webklex/laravel-git-hook
[link-author]: https://github.com/webklex