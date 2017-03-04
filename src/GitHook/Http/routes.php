<?php

/*
* File: routes.php
* Category: -
* Author: MSG
* Created: 04.03.17 15:54
* Updated: -
*
* Description:
*  -
*/

Route::post($this->app['config']['git-hook']['url'], 'Webklex\GitHook\Http\GitHookController@gitHook');