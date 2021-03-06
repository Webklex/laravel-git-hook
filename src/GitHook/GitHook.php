<?php

/*
* File: GitHook.php
* Category: -
* Author: MSG
* Created: 04.03.17 16:13
* Updated: -
*
* Description:
*  -
*/

namespace Webklex\GitHook;

use Illuminate\Support\Facades\Artisan;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Webklex\GitHook\Payload\Payload;

class GitHook {

    /**
     * Monolog Logger instance. Used for logging
     * @var Logger
     */
    protected $oLogger = Logger::class;

    /**
     * Holds the configuration. This is done due to performance improvements and reduce the application overhead
     * @var array|mixed
     */
    protected $config = [];

    /**
     * A Collection which holds the current Request
     * @var array
     */
    protected $aRequest = [];

    /**
     * Path to your git binary
     * @var mixed|string
     */
    protected $gitPath = 'git';

    /**
     * This variable might be set and used later on. It holds the current branch name
     * @var null
     */
    protected $currentBranch = null;

    /**
     * This variable might be set and used later on. It holds the pushed branch name
     * @var null
     */
    protected $pushedBranch = null;

    /**
     * GitHook constructor.
     */
    public function __construct() {
        $this->config = config('git-hook');
        $this->gitPath = !empty($this->config['git_path']) ? $this->config['git_path'] : $this->gitPath;

        $this->initLogger($this->config['logfile']);
    }

    /**
     * Initialize a new Logger instance
     * @param $name
     *
     * @return $this
     */
    public function initLogger($name){
        $this->oLogger = new Logger($name);
        $this->oLogger->pushHandler(new StreamHandler(storage_path('logs/'.$name.'.log'), Logger::WARNING));

        return $this;
    }

    /**
     * Get the current Logger instance
     *
     * @return Logger
     */
    public function getLogger(){
        return $this->oLogger;
    }

    /**
     * Parse the raw request content and convert it into a collection
     * @param $rawRequest string
     *
     * @return bool
     */
    public function parseRequest($rawRequest){
        $service = 'Webklex\GitHook\Payload\Services\\'.ucfirst($this->config['service']).'Service';

        /***
         * @var Payload $oPayload
         */
        $oPayload = with(new $service())->parsePayload($rawRequest);

        $this->aRequest = $oPayload->getPayload();

        $this->aRequest->put('commits', collect($this->aRequest->get('commits')));

        return $this->aRequest->count() > 0;
    }

    /**
     * Locate the Laravel root directory by trying to locate the .env and .git/config file
     */
    public function locateLaravelRoot(){
        if (empty($this->config['repo_path'])) {
            $this->config['repo_path'] = __DIR__;

            do {
                $this->config['repo_path'] = dirname($this->config['repo_path']);
            } while ($this->config['repo_path'] !== '/' && !file_exists($this->config['repo_path'].'/.env'));
        }
        
        if ($this->config['repo_path'] !== '/') {
            while ($this->config['repo_path'] !== '/' && !file_exists($this->config['repo_path'].'/.git/config')) {
                $this->config['repo_path'] = dirname($this->config['repo_path']);
            }
        }
    }

    /**
     * Get the current project branch
     *
     * @return string
     */
    public function getCurrentBranch(){
        if($this->currentBranch == null){
            $this->currentBranch = trim(
                shell_exec(
                    escapeshellcmd($this->gitPath) . ' --git-dir=' .
                    escapeshellarg($this->config['repo_path'] . '/.git') .  ' --work-tree=' .
                    escapeshellarg($this->config['repo_path']) . ' rev-parse --abbrev-ref HEAD'));
        }

        return $this->currentBranch;
    }

    /**
     * Get the pushed branch name
     *
     * @return string
     */
    public function getPushedBranch(){
        return $this->pushedBranch;
    }

    /**
     * Check if white listing is enabled and if so if the request comes from a white listed IP
     *
     * @return bool
     */
    public function whiteList(){
        if(!empty($this->config['allowed_sources'])){
            return in_array($_SERVER['REMOTE_ADDR'], $this->config['allowed_sources']);
        }

        return true;
    }

    /**
     * Check if the Repository exists
     *
     * @return bool
     */
    public function checkRepository(){
        if(!empty($this->config['repo_path'])){
            return file_exists($this->config['repo_path'].'/.git/config');
        }

        return true;
    }

    /**
     * Check if the pushed branch is the one we want to deploy
     *
     * @return bool
     */
    public function checkBranch(){
        $pushedBranch = explode('/', $this->aRequest->get('ref'));
        $this->pushedBranch = trim($pushedBranch[2]);

        return $this->getCurrentBranch() == $this->pushedBranch;
    }

    /**
     * Run an array of given commands
     * @param array $commands
     */
    protected function runCommands(array $commands){
        foreach($commands as $command){

            $cmd  = $command;
            $args = [];

            if(is_array($command)){
                $cmd  = $command[0];
                $args = $command[1];
            }

            Artisan::call($cmd, $args);
        }
    }

    /**
     * Perform the deployment task
     *
     * @return bool
     */
    public function gitPull(){
        $git_remote = !empty($this->config['remote']) ? $this->config['remote'] : 'origin';

        $cmd = escapeshellcmd($this->gitPath) . ' --git-dir=' .
               escapeshellarg($this->config['repo_path'] . '/.git') . ' --work-tree=' .
               escapeshellarg($this->config['repo_path']) . ' pull ' .
               escapeshellarg($git_remote) . ' ' .
               escapeshellarg($this->getCurrentBranch()) . ' >> ' .
               escapeshellarg($this->config['repo_path'] . '/storage/logs/git-hook.log');

        $this->runCommands($this->config['before_pull']);

        $pullRequest = shell_exec($cmd);

        $this->runCommands($this->config['after_pull']);

        return $this->notify([
            'cmd'   => $cmd,
            'user'  => shell_exec('whoami'),
            'response' => $pullRequest,
        ]);
    }

    /**
     * Notify users if this feature is enabled within the config file
     * @param array $result
     *
     * @return bool
     */
    protected function notify(array $result){
        if (!empty($this->config['email_recipients'])) {

            /* Convert the git message into a better readable format
             * */
            $aCommit = $this->aRequest->get('commits')->map(function($commit){

                /* Split message into subject + description
                 *    -Suggested by git: Assumes Git's recommended standard where first line is the main summary
                 * */
                $commit['human_id'] = substr($commit['id'], 0, 9);
                $commit['human_subject'] = strtok($commit['message'], "\n");
                $commit['human_description'] = $commit['message'];
                $commit['human_date'] = with(new \DateTime($commit['timestamp']))->format('d.m.Y H:i');

                return $commit;
            });
            $this->aRequest->put('commits', $aCommit->all());

            /* Set sender address
             * */
            $sender = config('mail.from');
            if (!empty($this->config['email_sender']['address'])) {
                $sender = $this->config['email_sender'];
            }

            /* Set email recipients
             * */
            $sender['recipients'] = $this->config['email_recipients'];

            /* Convert the request collection back into an array
             * */
            $aRequest = $this->aRequest->all();

            /* Send the actual information email
             * */
            \Mail::send('git-hook::email', [ 'server' => $result, 'git' => $aRequest ], function($message) use ($aRequest, $sender) {

                $message->from($sender['address'], $sender['name']);

                foreach ($sender['recipients'] as $recipient) {
                    $message->to($recipient['address'], $recipient['name']);
                }

                $message->subject('Repo: ' . $aRequest['repository']['name'] . ' updated');
            });
        }

        return true;
    }
}