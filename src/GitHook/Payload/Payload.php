<?php
/*
* File: Payload.php
* Category: -
* Author: MSG
* Created: 05.03.17 05:41
* Updated: -
*
* Description:
*  -
*/

namespace Webklex\GitHook\Payload;

class Payload {

    /**
     * Payload holder
     * @var array $aPayload
     */
    protected $aPayload = [];

    /**
     * Map all required attributes
     * @var array $map
     */
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

    /**
     * Cast any mapped value to a given schema
     * @var array $casts
     */
    protected $casts = [];

    /**
     * Payload constructor.
     */
    public function __construct() {}

    /**
     * Parse a payload string into the service
     * @param $payload
     *
     * @return $this
     */
    public function parsePayload($payload){
        if($this->isJson($payload)){
            $this->aPayload = $this->validate(json_decode($payload, true), $this->map);
            $this->castPayload();
        }

        return $this;
    }

    /**
     * Check if a given string is valid JSON
     * @param $string string
     * @return bool
     */
    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Validate and map a given payload recursive
     * @param array $aPayload
     * @param array $aMap
     *
     * @return array
     */
    public function validate(array $aPayload, array $aMap){
        $payload = [];
        foreach($aMap as $key => $map){
            if($map === ''){
                $payload[$key] = '';
            }elseif(isset($aPayload[$key])){
                if(is_array($aPayload[$key])){
                    $payload[$key] = $this->validate($aPayload[$key], $map);
                }elseif($map != null){
                    $payload[$key] = $this->getPayloadValue($map, $aPayload);
                }else{
                    $payload[$key] = $aPayload[$key];
                }
            }elseif(is_array($map)){
                $payload[$key] = [];

                foreach($map as $k => $m){
                    if(is_array($m)){
                        $payload[$key][$k] = $this->validate($aPayload, $m);
                    }elseif($m != null){
                        $payload[$key][$k] = $this->getPayloadValue($m, $aPayload);
                    }elseif($m === ''){
                        $payload[$key][$k] = '';
                    }else{
                        $payload[$key][$k] = $aPayload[$key][$k];
                    }
                }
            }elseif($map != null){
                $payload[$key] = $this->getPayloadValue($map, $aPayload);
            }
        }
        return $payload;
    }

    /**
     * Get a payload value from a given compact string index
     * @param $key
     * @param array $payload
     *
     * @return array|mixed
     */
    protected function getPayloadValue($key, array $payload){
        $keys = explode('.', $key);
        foreach($keys as $i => $key){
            unset($keys[$i]);
            if(isset($payload[$key])){
                $payload = $payload[$key];
            }
        }

        return $payload;
    }

    /**
     * Cast available casts on the current payload
     */
    protected function castPayload(){
        foreach($this->casts as $string => $cast){
            $keys = explode('.', $string);

            $key = $keys[0];
            unset($keys[0]);
            sort($keys);

            $this->aPayload = $this->replace($this->aPayload, $key, $keys, $cast);
        }
    }

    /**
     * Cast a specific value within the payload
     * @param $arr array
     * @param $key string
     * @param $keys array
     * @param $cast string
     *
     * @return mixed
     */
    protected function replace($arr, $key, $keys, $cast){
        if(!empty($keys)){
            $kkey = $keys[0];
            unset($keys[0]);
            sort($keys);
            $arr[$key] = $this->replace($arr[$key], $kkey, $keys, $cast);
        }else{
            $arr[$key] = str_replace('{$1}', $arr[$key], $cast);
        }
        return $arr;
    }

    /**
     * Get the current payload
     * @return \Illuminate\Support\Collection
     */
    public function getPayload(){
        return collect($this->aPayload);
    }
}