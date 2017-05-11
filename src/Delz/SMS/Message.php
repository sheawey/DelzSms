<?php

namespace Delz\SMS;

use Delz\SMS\Contract\IMessage;
use Delz\SMS\Contract\IProvider;
use Delz\SMS\Exception\InvalidStateException;

/**
 * 发送的短信消息对象
 *
 * @package Delz\SMS
 */
class Message implements IMessage
{
    /**
     * 发送Id
     *
     * @var string
     */
    protected $id;

    /**
     * 手机号码
     *
     * @var string
     */
    protected $to;

    /**
     * 发送的内容
     *
     * @var string
     */
    protected $content;

    /**
     * 第三方发送方
     *
     * @var IProvider
     */
    protected $provider;

    /**
     * 发送状态
     *
     * @var string
     */
    protected $state;

    /**
     * 消息创建时间
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * 发送时间
     *
     * @var \DateTime
     */
    protected $sentAt;

    /**
     * 用户接收时间
     *
     * @var \DateTime
     */
    protected $deliveredAt;

    /**
     * 发送失败原因
     *
     * @var string
     */
    protected $failReason;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->state = IMessage::STATE_NEW;
        $this->createdAt = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setTo($mobile)
    {
        $this->to = $mobile;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content, $vars = [])
    {
        if(!is_string($content)) {
            throw new \InvalidArgumentException("content must be a string.");
        }

        $this->content = $content;

        if(!empty($vars)) {
            $this->content = $this->replaceContent($content, $vars);
        }

        return $this;
    }

    /**
     * 将$content的变量，用#分割开，如#content#
     * $vars中有变量['content'=>'abc'],那么$content中的#content#将会替换成abc
     *
     * @param string $content
     * @param array $vars
     * @return string
     */
    protected function replaceContent($content, $vars = [])
    {
        $find = [];
        $replace =[];
        foreach($vars as $k => $v) {
            $find[] = '#' . $k . '#';
            $replace[] = $v;
        }
        return str_ireplace($find, $replace, $content);
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
    public function getState()
    {
        return $this->state;
    }

    /**
     * {@inheritdoc}
     */
    public function setState($state)
    {
        $state = strtolower($state);
        $states = [
            IMessage::STATE_NEW,
            IMessage::STATE_QUEUED,
            IMessage::STATE_SENT,
            IMessage::STATE_DELIVERED,
            IMessage::STATE_FAIL
        ];

        if(!in_array($state, $states)) {
            throw new InvalidStateException("invalid message state.");
        }

        $this->state = $state;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setSentAt(\DateTime $sentAt)
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDeliveredAt()
    {
        return $this->deliveredAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setDeliveredAt(\DateTime $deliveredAt)
    {
        $this->deliveredAt = $deliveredAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFailReason()
    {
        return $this->failReason;
    }

    /**
     * {@inheritdoc}
     */
    public function setFailReason($failReason)
    {
        $this->failReason = $failReason;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function send()
    {
        if(!$this->provider) {
            throw new \RuntimeException('No provider found');
        }
        return $this->provider->send($this);
    }

}