<?php

namespace Delz\SMS\Contract;

/**
 * 短信供应商接口
 *
 * @package Delz\SMS\Contract
 */
interface IProvider
{
    /**
     * 发送消息
     *
     * @param IMessage $message
     * @return IResult
     */
    public function send(IMessage $message);

    /**
     * 获取状态报告
     *
     * @return array|IReport[]
     */
    public function report();

    /**
     * 获取短信供应商名称
     *
     * @return string
     */
    public function getName();
}