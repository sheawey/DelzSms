<?php

namespace Delz\SMS;

use Delz\SMS\Contract\IMessage;
use Delz\SMS\Contract\IResult;

/**
 * 短信发送结果类
 *
 * @package Delz\SMS
 */
class Result implements IResult
{
    /**
     *
     * @var IMessage
     */
    protected $message;

    /**
     * @param IMessage $message
     */
    public function __construct(IMessage $message)
    {
        $this->message = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return (bool)$this->message->getState() == IMessage::STATE_SENT;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }
}