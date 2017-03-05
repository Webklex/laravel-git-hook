<?php

/*
* File: BitbucketService.php
* Category: -
* Author: MSG
* Created: 05.03.17 06:08
* Updated: -
*
* Description:
*  An example payload can be found here:
*  https://confluence.atlassian.com/bitbucket/event-payloads-740262817.html#EventPayloads-Push
*/

namespace Webklex\GitHook\Payload\Services;

use Webklex\GitHook\Payload\Payload;

class BitbucketService extends Payload {

    protected $map = [
        'commits' => [[
            'id'        => 'push.changes.0.commits.0.hash',
            'timestamp' => 'push.changes.0.commits.0.date',
            'message'   => 'push.changes.0.commits.0.hash',
            'url'   => 'push.changes.0.commits.0.links.html.href',
            'author'    => [
                'name'  => 'push.changes.0.commits.0.author.user.display_name',
                'email' => '',
            ]
        ]],
        'repository' => [
            'name'      => 'repository.name',
            'description' => '',
            'url'       => 'repository.links.html.href',
            'homepage'  => 'repository.website',
        ],
        'ref'       => 'push.changes.0.new.name',
        'user_name' => 'push.changes.0.commits.0.author.user.display_name',
        'before'    => 'push.changes.0.new.target.parents.0.hash',
        'after'     => 'push.changes.0.new.target.hash',
    ];

    protected $casts = [
        'ref' => 'ref/branch/{$1}'
    ];

}