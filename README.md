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

## Usage

This library is designed to handle the automatic deployment by git hooks 
as simple as possible. There isn't much todo to get started: just add the
Provider and edit the `config/git-hook.php` file to make it fit your needs.


Custom configuration can be made within the `config/git-hook.php` file:

Get notified by mail. Just add your credentials: 
```
'email_recipients' => [
    [
        ['name' => 'Admin', 'address' => 'email@example.com'],
        ...
    ]
],
```
 
Specify a custom email sender address:
```
'email_sender' => ['address' => null, 'name' => null],
```

Perhaps your repository is somehow specially structured, if that's the case, specify yozr
repository path below:
```
'repo_path' => '',
```

If you want to secure the deployment process a bit more, whitelist the repository IPs:
```
'allowed_sources' => [],
```

Your remote branch
```
'remote' => 'origin',
```

Where is the git binary located? By default /usr/bin/git will be used.
```
'git_path' => '',
```

How sould the logfile be named?
```
'logfile' => 'git-hook',
```

Define your remote git service. This is required to identify the payload.
Currently supported: `github`, `gitbucket`
```
'service' => 'github',
```

How should your deployment url (git hook) look like? You can be as creative as you want ;)
If you are concerned someone could guess it, use a more cryptic url such as: `JHFUjhd67567JHFGhsd78236784wegfJHFghdgf`
```
'url' => 'git-hook'
```

## Potential problems:

Please make sure your `www-data` user can actually perform a git pull on the server without 
having to enter a password:
so you might want to take a look at ssh-keys or something similar

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