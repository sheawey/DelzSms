<?php

namespace Delz\SMS;

use Delz\SMS\Contract\IReport;

/**
 * 短信发送报告
 *
 * @package Delz\SMS
 */
class Report implements IReport
{
    /**
     * 消息代号
     *
     * @var string
     */
    protected $id;

    /**
     * 接收到的时间
     *
     * @var \DateTime
     */
    protected $deliveredAt;

    /**
     * 发送失败原因
     *
     * @var string
     */
    protected $errorMessage;

    /**
     * 第三方发送方名称
     *
     * @var string
     */
    protected $providerName;

    /**
     * 用户是否接收到
     *
     * @var bool
     */
    protected $success;

    public function __construct($id, $success, $providerName, \DateTime $deliveredAt, $errorMessage)
    {
        $this->id = $id;
        $this->success = (bool)$success;
        $this->providerName = $providerName;
        $this->deliveredAt = $deliveredAt;
        $this->errorMessage = $errorMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return $this->success;
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
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function getDeliveredAt()
    {
        return $this->deliveredAt;
    }
}