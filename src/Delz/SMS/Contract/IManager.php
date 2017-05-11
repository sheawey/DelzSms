<?php

namespace Delz\SMS\Contract;

/**
 * 短信发管理类接口
 *
 * @package Delz\SMS\Contract
 */
interface IManager
{
    /**
     * 获取发送服务对象
     *
     * @return IProvider
     */
    public function getProvider();

    /**
     * 设置发送服务对象
     *
     * @param IProvider $provider
     * @return IMessage
     */
    public function setProvider(IProvider $provider);

    /**
     * 创建消息对象
     *
     * @return IMessage
     */
    public function createMessage();

    /**
     * 获取报告
     *
     * @return IReport[]
     */
    public function getReports();

    /**
     * 发送短信
     *
     * @param string $mobile
     * @param string $content
     * @return IResult
     */
    public function send($mobile, $content);
}