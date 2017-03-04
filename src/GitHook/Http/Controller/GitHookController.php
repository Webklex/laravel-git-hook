<?php
/*
* File: GitHookController.php
* Category: Controller
* Author: MSG
* Created: 04.03.17 15:54
* Updated: -
*
* Description:
*  -
*/

namespace Webklex\GitHook\Http;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webklex\GitHook\GitHook;


class GitHookController extends Controller {

    /**
     * GitHook instance holder
     * @var GitHook
     */
    protected $oGitHook = GitHook::class;

    /**
     * GitHookController constructor.
     */
    public function __construct() {
        $this->oGitHook = new GitHook();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function gitHook(Request $request)
    {

        /* Check if the request comes from a white listed ip
         * Only relevant if you use the white list functionality
         * */
        if ($this->oGitHook->whiteList() == false) {
            $this->oGitHook->getLogger()->addError('Request must come from an approved IP');
            return response()->json([
                'success' => false,
                'message' => 'Request must come from an approved IP',
            ], 500);
        }


        /* Check if the current request contains any information and isn't empty
         * */
        if ($this->oGitHook->parseRequest($request->getContent()) == false) {
            $this->oGitHook->getLogger()->addError('Web hook data does not look valid');
            return response()->json([
                'success' => false,
                'message' => 'Web hook data does not look valid',
            ], 500);
        }

        $this->oGitHook->locateLaravelRoot();

        /* Check if the repository actually exists
         * */
        if ($this->oGitHook->checkRepository() == false) {
            $this->oGitHook->getLogger()->addError('Invalid repo path in config');
            return response()->json([
                'success' => false,
                'message' => 'Invalid repo path in config',
            ], 500);
        }


        /* Check if the pushed branch is the one we one want to deploy
         * */
        if ($this->oGitHook->checkBranch() == false){
            $this->oGitHook->getLogger()->addWarning('Pushed refs do not match current branch');
            return response()->json([
                'success' => false,
                'message' => 'Pushed refs do not match current branch',
            ], 500);
        }


        return response()->json($this->oGitHook->gitPull());
    }
}