<?php

namespace Delz\SMS\Provider;

use Delz\Common\Util\Http;
use Delz\SMS\Contract\IMessage;
use Delz\SMS\Contract\IProvider;
use Delz\SMS\Result;
use Delz\SMS\Report;
use GuzzleHttp\Exception\ConnectException;

/**
 * 大汉三通短信接口实现（http://3tong.net）
 *
 * @package Delz\SMS\Provider
 */
class Tong3 implements IProvider
{
    /**
     * 短信下行网关地址
     */
    const API_SUBMIT_URL = 'http://wt.3tong.net/json/sms/Submit';

    /**
     * 短信发送报告
     *
     * 每次最多取200条状态报告。
     */
    const API_REPORT = 'http://wt.3tong.net/json/sms/Report';

    /**
     * 用户账号
     *
     * @var string
     */
    protected $account;

    /**
     * 账号密码，需采用MD5加密(32位小写)
     *
     * @var string
     */
    protected $password;

    /**
     * 短信签名，该签名需要提前报备，生效后方可使用，不可修改，必填
     * 示例如：【大汉三通】
     *
     * @var string
     */
    protected $sign;

    /**
     * 短信签名对应子码(大汉三通提供)+自定义扩展子码(选填)，必须是数字，选填，
     * 未填使用签名对应子码
     *
     * @var string
     */
    protected $subCode;

    /**
     * @param string $account 用户账号
     * @param string $password 账号密码
     * @param string $sign 短信签名
     * @param string $subCode 短信签名对应子码
     */
    public function __construct($account, $password, $sign, $subCode)
    {
        $this->account = $account;
        $this->password = $password;
        $this->sign = $sign;
        $this->subCode = $subCode;
    }

    /**
     * {@inheritdoc}
     */
    public function send(IMessage $message)
    {
        $params = [
            'account' => $this->account,
            'password' => md5($this->password),
            'msgid' => $this->buildMessageId(),//该批短信编号(32位UUID)，需保证唯一，必填
            'phones' => $message->getTo(),//接收手机号码，多个手机号码用英文逗号分隔，最多500个
            'content' => $message->getContent(),//短信内容，最多350个汉字
            'sign' => $this->sign,
            'subcode' => $this->subCode,
            'sendtime' => '' //定时发送时间，格式yyyyMMddHHmm，为空或早于当前时间则立即发送；
        ];
        try {
            $response = Http::post(self::API_SUBMIT_URL, ['json' => $params, 'timeout' => 10]);
            $responseArr = json_decode($response->getBody(), true);
            $message->setSentAt(new \DateTime());
            if ($responseArr['result'] === '0') {
                $message->setState(IMessage::STATE_SENT);
                $message->setId($params['msgid']);
            } else {
                $message->setState(IMessage::STATE_FAIL);
                $message->setFailReason($responseArr['desc']);
            }
            return new Result($message);
        } catch (ConnectException $e) {
            $message->setSentAt(new \DateTime());
            $message->setState(IMessage::STATE_FAIL);
            $message->setFailReason($e->getMessage());
            return new Result($message);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function report()
    {
        $params = [
            'account' => $this->account,
            'password' => md5($this->password),
        ];
        $result = [];

        $response = Http::post(self::API_REPORT, ['json' => $params, 'timeout' => 10]);
        $responseArr = json_decode($response->body, true);
        if ($responseArr['result'] === '0') {
            foreach ($responseArr['reports'] as $res) {
                $success = $res['status'] === '0' ? true : false;
                $deliveredAt = new \DateTime($res['time'], new \DateTimeZone('Asia/Shanghai'));
                $result[] = new Report($res['msgid'], $success, $this->getName(), $deliveredAt, $res['desc']);
            }
        } else {
            throw new \Exception($responseArr['desc']);
        }

        return $result;
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'tong3';
    }

    /**
     * 生成messageId
     *
     * @return string
     */
    private function buildMessageId()
    {
        return md5(date('ymd') . substr(time(), -5) . str_pad(rand(0, 999999), 6, "0", STR_PAD_LEFT));
    }
}