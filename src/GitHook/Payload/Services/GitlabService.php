<?php

/*
* File: GitlabService.php
* Category: -
* Author: AA
* Created: 08.11.17 3:20
* Updated: -
*/


namespace Webklex\GitHook\Payload\Services;

use Webklex\GitHook\Payload\Payload;

class GitlabService extends Payload {

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