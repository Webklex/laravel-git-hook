<?php
/*
* File: git-hook.php
* Category: Config
* Author: MSG
* Created: 04.03.17 15:57
* Updated: -
*
* Description:
*  -
*/

return [


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
    'git_path' => '/usr/bin/git',


    /*
    |--------------------------------------------------------------------------
    | Logfile name
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
    | Remote git service
    |--------------------------------------------------------------------------
    |
    | Please select a service. This is required in order to parse the delivered
    | payload.
    |
    | Available services:
    | github       [default]
    | bitbucket
    |
    */
    'service' => 'github',


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
    'url' => 'git-hook',


    /*
    |--------------------------------------------------------------------------
    | Before pull event
    |--------------------------------------------------------------------------
    |
    | If you have any commands that have to be called before a pull event, specify
    | them below.
    |
    | ['route:clear', ['some:command', ['arg1' => 1]]]
    |
    */
    'before_pull' => [],


    /*
    |--------------------------------------------------------------------------
    | After pull event
    |--------------------------------------------------------------------------
    |
    | If you have any commands that have to be called after a pull event, specify
    | them below.
    |
    | ['route:clear', ['some:command', ['arg1' => 1]]]
    |
    */
    'after_pull' => []


];