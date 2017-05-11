<?php

namespace Delz\SMS\Contract;

use Delz\SMS\Exception\InvalidStateException;

/**
 * 发送的短信消息接口类
 *
 * 发送状态说明：
 *
 * 1. 一条消息创建，状态是 STATE_NEW
 * 2. 如果要异步发送，那么可以放入队列，状态为 STATE_QUEUED （自己业务的队列，进入第三方服务的队列不算这个状态）
 * 3. 调用第三方服务网关发送，进入第三方服务发送队列，如果返回成功，状态为 STATE_SENT，如果失败，返回 STATE_FAIL
 * 4. 调用第三方服务发送报告，如果用户已接收，返回 STATE_SENT， 如果返回错误， 返回 STATE_FAIL
 *
 * 发送成功，状态最后为 STATE_SENT。
 * 发送失败 STATE_FAIL，有两种可能性：一种是在第三方服务网关发送失败，一种是在电信网关时发送失败
 *
 * @package Delz\SMS\Contract
 */
interface IMessage
{
    /**
     * 消息状态：新建
     */
    const STATE_NEW = 'new';

    /**
     * 消息状态：已发送
     */
    const STATE_SENT = 'sent';

    /**
     * 消息状态：已接收
     */
    const STATE_DELIVERED = 'delivered';

    /**
     * 消息状态：发送失败
     */
    const STATE_FAIL = 'fail';

    /**
     * 消息状态：队列中
     */
    const STATE_QUEUED = 'queued';

    /**
     * 信息Id
     *
     * @return string
     */
    public function getId();

    /**
     * 设置信息Id
     *
     * 一般不用在实例化Message的时候设置，在发送完毕后由第三方返回
     *
     * @param string $id
     * @return IMessage
     */
    public function setId($id);

    /**
     * @param string $mobile
     * @return IMessage
     */
    public function setTo($mobile);

    /**
     * @return string
     */
    public function getTo();

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content 短信内容
     * @param array $vars 参数
     * @throws \InvalidArgumentException
     * @return IMessage
     */
    public function setContent($content, $vars = []);

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
     * @return string
     */
    public function getState();

    /**
     * 设置消息状态
     *
     * 如果消息状态设置不正确，抛出InvalidStateException异常
     *
     * @param string $state
     * @return IMessage
     * @throws InvalidStateException
     */
    public function setState($state);

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param \DateTime $createdAt
     * @return IMessage
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * @return \DateTime
     */
    public function getSentAt();

    /**
     * @param \DateTime $sentAt
     * @return IMessage
     */
    public function setSentAt(\DateTime $sentAt);

    /**
     * @return \DateTime
     */
    public function getDeliveredAt();

    /**
     * @param \DateTime $deliveredAt
     * @return IMessage
     */
    public function setDeliveredAt(\DateTime $deliveredAt);

    /**
     * 如果发送失败，发送失败的原因
     *
     * @return string
     */
    public function getFailReason();

    /**
     * 设置发送失败原因
     *
     * @param string $failReason
     */
    public function setFailReason($failReason);

    /**
     * 发送短信
     *
     * @return IResult
     */
    public function send();
}