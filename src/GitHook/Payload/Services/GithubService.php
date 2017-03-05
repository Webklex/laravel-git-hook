<?php

/*
* File: GithubService.php
* Category: -
* Author: MSG
* Created: 05.03.17 18:31
* Updated: -
*
* Description:
*  An example payload can be found here:
*  https://developer.github.com/v3/activity/events/types/#pushevent
*/


namespace Webklex\GitHook\Payload\Services;

use Webklex\GitHook\Payload\Payload;

class GithubService extends Payload {

    protected $map = [
        'commits' => [[
            'id' => null,
            'timestamp' => null,
            'message' => null,
            'url' => null,
            'author' => [
                'name' => null,
                'email' => null,
            ],
        ]],
        'repository' => [
            'name' => null,
            'description' => null,
            'url' => null,
            'homepage' => null,
        ],
        'ref' => null,
        'user_name' => null,
        'before' => null,
        'after' => null,
    ];

    protected $casts = [];

}