<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
namespace HyperfTest;

use Hyperf\DbConnection\Db;
use PHPUnit\Framework\TestCase;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Tusimo\Resource\Constants\Header;
use Tusimo\Resource\Entity\RequestContext;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Class HttpTestCase.
 * @method get($uri, $data = [], $headers = [])
 * @method post($uri, $data = [], $headers = [])
 * @method json($uri, $data = [], $headers = [])
 * @method file($uri, $data = [], $headers = [])
 * @method request($method, $path, $options = [])
 * @mixin RequestContext
 */
abstract class FrameworkTestCase extends TestCase
{
    protected RequestContext $context;

    /**
     * Default configs from apollo.
     *
     * @var array
     */
    protected $baseConfigs = [
        'servers' => [
            'user' => 'http://user',
            'social' => 'http://social',
            'live' => 'http://live',
            'shield' => 'http://shield',
        ],
        'app' => [
            'name' => 'auth',
            'auth' => 'development',
        ],
        'services' => [
            'translation' => 'http://translation/api',
            'broadcast' => 'http://broadcast/api',
            'notices' => 'http://notices/api',
            'auth' => 'http://auth/api',
        ],
    ];

    protected $configs = [];

    /**
     * DEfault context use for test.
     *
     * @var array
     */
    protected $defaultContexts = [
        Header::X_USER_ID => 404,
        Header::X_CONSUMER_NAME => 'testing',
        Header::X_APP => 'default',
        Header::X_CLIENT_PLATFORM => 'ios',
        Header::X_CLIENT_VERSION => 'v1.0.0',
        Header::X_CLIENT_VERSION_CODE => '100',
        Header::X_CLIENT_PACKAGE => 'testing.suits',
        Header::X_CLIENT_APP_NAME => 'default',
        Header::X_CLIENT_DEVICE_ID => '3a:11:fb:7d:19:43',
        Header::X_CLIENT_CHANNEL => 'facebook-100',
        Header::X_REAL_IP => '127.0.0.1',
        Header::X_CLIENT_REFER => 'http://127.0.0.1/',
        Header::X_REQUEST_ID => '1000-1111-2222-3333',
        Header::X_LANGUAGE => 'zh-CN',
    ];

    /**
     * Make a fresh environment for test case.
     */
    public function setUp(): void
    {
        $this->flushRedis();
        if (is_testing()) {
            $this->runCommand('migrate:fresh', ['--force']);
        }

        $this->context = RequestContext::createFromArray($this->defaultContexts);
        parent::setUp();
        $this->initConfigs();
    }

    /**
     * Clean up test case.
     * rollback all database change.
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->initConfigs();
    }

    protected function flushRedis()
    {
        $redis = $this->getContainer()->get(\Hyperf\Redis\Redis::class);
        $redis->flushAll();
    }

    /**
     * Init default configs for each test case.
     */
    protected function initConfigs()
    {
        $this->setConfigs(
            array_merge($this->baseConfigs, $this->configs)
        );
    }

    /**
     * Set config in test config
     * only work in a single test case.
     * @param string $key
     * @param mixed $value
     */
    protected function setConfig($key, $value)
    {
        $config = $this->getContainer()->get(ConfigInterface::class);
        $config->set($key, $value);
        return $this;
    }

    /**
     * Set configs in test config
     * only work in a single test case.
     * @param string $key
     * @param mixed $value
     */
    protected function setConfigs(array $configs)
    {
        foreach ($configs as $key => $value) {
            $this->setConfig($key, $value);
        }
    }

    /**
     * Get application container.
     */
    protected function getContainer(): ContainerInterface
    {
        return ApplicationContext::getContainer();
    }

    /**
     * Set Current User Id.
     * @param mixed $userId
     * @return $this
     */
    protected function AsUser($userId): self
    {
        $this->context->setUserId($userId);
        return $this;
    }

    /**
     * Set Current App.
     *
     * @param string $app
     */
    protected function asApp($app): self
    {
        $this->context->setApp($app);
        return $this;
    }

    /**
     * Get current headers.
     */
    protected function getHeaders(): array
    {
        return $this->context->toHeaders();
    }

    protected function flushDB(string $table)
    {
        Db::table($table)->truncate();
    }

    protected function runCommand(string $command, array $args = [])
    {
        $params = array_merge([
            'command' => $command,
        ], $args);
        $input = new ArrayInput($params);
        $output = new NullOutput();

        /** @var \Psr\Container\ContainerInterface $container */
        $container = $this->getContainer();

        /** @var \Symfony\Component\Console\Application $application */
        $application = $container->get(\Hyperf\Contract\ApplicationInterface::class);
        $application->setAutoExit(false);

        return $application->run($input, $output);
    }
}
