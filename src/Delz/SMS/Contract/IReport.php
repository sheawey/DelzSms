<?php

namespace Delz\SMS\Contract;

/**
 * 发送报告类接口
 *
 * @package Delz\SMS\Contract
 */
interface IReport
{
    /**
     * 消息代号
     *
     * @return string
     */
    public function getId();

    /**
     * 用户是否接收到
     *
     * @return boolean
     */
    public function isSuccessful();

    /**
     * 第三方发送方名称
     *
     * @return string
     */
    public function getProviderName();

    /**
     * 发送失败原因
     *
     * @return string
     */
    public function getErrorMessage();

    /**
     * 接收到的时间
     *
     * @return \DateTime
     */
    public function getDeliveredAt();

}