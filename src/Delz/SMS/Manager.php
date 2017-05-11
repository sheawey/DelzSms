<?php

namespace Delz\SMS;

use Delz\SMS\Contract\IManager;
use Delz\SMS\Contract\IProvider;
use Delz\SMS\Exception\ProviderNotFoundException;

/**
 * 短信发送管理器
 *
 * @package Delz\SMS
 */
class Manager implements IManager
{
    /**
     * 短信发送服务提供者
     *
     * @var IProvider
     */
    protected $provider;

    /**
     * @param IProvider|null $provider
     */
    public function __construct(IProvider $provider = null)
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * {@inheritdoc}
     */
    public function setProvider(IProvider $provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getReports()
    {
        $this->check();
        return $this->provider->report();
    }

    /**
     * {@inheritdoc}
     */
    public function createMessage()
    {
        $this->check();
        $message = new Message();
        $message->setProvider($this->provider);
        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function send($mobile, $content)
    {
        return $this->createMessage()->setTo($mobile)->setContent($content)->send();
    }

    /**
     * 检查是否注册了发送对象
     */
    private function check()
    {
        if(!$this->provider) {
            throw new ProviderNotFoundException('provider not found.');
        }
    }


}