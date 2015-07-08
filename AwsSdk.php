<?php
/**
 * @author Ray Jonathan Palpallatoc <rj.palpallatoc@gmail.com>
 * @date 7/6/2015
 * @time 2:13 AM
 */

namespace jenn0pal\aws;


use Aws\Sdk;
use yii\base\Component;
use yii\base\InvalidConfigException;

class AwsSdk extends Component
{
    public $key;
    public $secret;
    public $region;
    public $version = 'latest';

    //additional options
    public $options = [];

    public $configFile = false;

    private $_config;

    /**
     * @var Sdk $_sdk
     */
    private $_sdk = null;

    public function init()
    {
        if ($this->configFile == false) {
            $this->_config = [
                'credentials' => [
                    'key' => $this->key,
                    'secret' => $this->secret,
                ],
                'region' => $this->region,
                'version' => $this->version,
            ];
            $this->_config = array_merge($this->_config, $this->options);

        } else {
            if (!file_exists($this->configFile)) {
                throw new InvalidConfigException("{$this->configFile} does not exist");
            }
            $this->_config = $this->configFile;
        }

    }

    public function getSdk()
    {
        if ($this->_sdk === null) {
            $this->_sdk = new Sdk($this->_config);
        }
        return $this->_sdk;
    }

    public function __call($method, $params)
    {
        $sdk = $this->getSdk();
        if (is_callable([$sdk, $method]))
            return call_user_func_array(array($sdk, $method), $params);
        return parent::__call($method, $params);
    }

}