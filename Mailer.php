<?php

namespace ilnicki\mailgunmailer;

use Yii;
use yii\base\InvalidConfigException;
use yii\mail\BaseMailer;

use Mailgun\Mailgun;

/**
 * Mailer implements a mailer based on Mailgun.
 *
 * To use Mailer, you should configure it in the application configuration like the following,
 *
 * ~~~
 * 'components' => [
 *     ...
 *     'mailer' => [
 *         'class' => 'ilnicki\mailer\Mailer',
 *         'domain' => 'example.com',
 *         'key' => 'key-somekey',
 *         'tags' => ['yii'],
 *         'enableTracking' => false,
 *     ],
 *     ...
 * ],
 * ~~~
 * 
 * @package ilnicki\mailgunmailer
 *
 * @property-read Mailgun $mailgunMailer
 */
class Mailer extends BaseMailer
{

    /**
     * [$messageClass description]
     * @var string message default class name.
     */
    public $messageClass = Message::class;

    public $domain;
    public $key;

    public $fromAddress;
    public $fromName;
    public $tags = [];
    public $campaignId;
    public $enableDkim;
    public $enableTestMode;
    public $enableTracking;
    public $clicksTrackingMode; // true, false, "html"
    public $enableOpensTracking;

    private $mailgunMailerInstance;

    /**
     * @inheritdoc
     */
    protected function sendMessage($message)
    {
        $mailer = $this->getMailgunMailer();

        $message->setClickTracking($this->clicksTrackingMode)
            ->addTags($this->tags);

        Yii::info('Sending email.', __METHOD__);
        $response = $mailer->sendMessage($this->domain,
            $message->getMessage(),
            $message->getFiles()
        );

        Yii::info('Response: ' . print_r($response, true), __METHOD__);

        return true;
    }

    /**
     * @return Mailgun Mailgun mailer instance.
     */
    public function getMailgunMailer()
    {
        if (!is_object($this->mailgunMailerInstance)) {
            $this->mailgunMailerInstance = new Mailgun($this->key);
        }

        return $this->mailgunMailerInstance;
    }
}
