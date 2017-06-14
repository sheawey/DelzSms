<?php

namespace Delz\SMS\Contract;

/**
 * 发送结果接口类
 *
 * @package Delz\SMS\Contract
 */
interface IResult
{
    /**
     * 是否发送成功
     *
     * @return bool
     */
    public function isSuccessful();

    /**
     * 返回发送结果的目标消息对象
     *
     * @return IMessage
     */
    public function getMessage();

    /**
     * 获取消息Id
     *
     * @return string
     */
    public function getMessageId();
}