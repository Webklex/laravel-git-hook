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

| Parameter             | Default                             | Options                                                             | Description                                                                                               |
| --------------------- | :---------------------------------: | :------------------------------------------------------------------:| --------------------------------------------------------------------------------------------------------: |
| email_recipients      | []                                  | [ ['name' => 'Admin', 'address' => 'email@example.com'], ... ]      | Get notified by mail. Just add your credentials                                                           |
| email_email_sender    | ['address' => null, 'name' => null] | ['address' => null, 'name' => null]                                 | Specify a custom email sender address                                                                     |
| repo_path             | null                                | Leave empty to auto detect the vcs root                             | Perhaps your repository is somehow specially structured, if that's the case, specify your repository path |
| allowed_sources       | []                                  | ['192.168.1.1', '192.168.1.2', ...]                                 | If you want to secure the deployment process a bit more, whitelist the remote repository IPs              |
| remote                | origin                              |                                                                     | Your remote branch name                                                                                   |
| git_path              | /usr/bin/git                        |                                                                     | Where is the git binary located                                                                           |
| logfile               | git-hook                            |                                                                     | Name of the logfile. It will be stored under storage/logs                                                 |
| service               | github                              | `github`, `gitbucket`                                               | Define your remote git service. This is required to identify the payload                                  |
| url                   | git-hook                            |                                                                     | Define the deployment url. Keep in mind, that the given parameter will be added to your app.url           |
| before_pull           | []                                  | ['down', ['some:command', ['arg1' => 1]]]                           | If you have any commands that have to be called before a pull event, specify them here                    |
| after_pull            | []                                  | ['route:clear', ['some:command', ['arg1' => 1]], 'up']              | If you have any commands that have to be called after a pull event, specify them here                     |


If you are concerned someone could guess it, use a more cryptic url such as: `JHFUjhd67567JHFGhsd78236784wegfJHFghdgf`


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