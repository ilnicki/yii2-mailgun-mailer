<?php

namespace ilnicki\mailgunmailer;

use yii\base\NotSupportedException;
use yii\mail\BaseMessage;
use Mailgun\Messages\MessageBuilder;

/**
 * Message implements a message class based on Mailgun MessageBuilder.
 *
 * @author Katanyoo Ubalee <ublee.k@gmail.com>
 * @author Dmytro Ilnicki <dmytro@ilnicki.me>
 */
class Message extends BaseMessage
{
    /**
     * @var MessageBuilder Mailgun message instance.
     */
    private $messageBuilder;

    public function init()
    {
        $this->messageBuilder = new MessageBuilder();
    }

    /**
     * @inheritdoc
     */
    public function getCharset()
    {
        return 'utf-8';
    }

    /**
     * @inheritdoc
     */
    public function setCharset($charset)
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFrom()
    {
        return @$this->messageBuilder->getMessage()['from'];
    }

    /**
     * @inheritdoc
     */
    public function setFrom($from)
    {
        if (!is_array($from)) {
            $from = explode(', ', $from);
        }

        foreach ($from as $email => $name) {
            if(is_string($email)) {
                $this->messageBuilder->setFromAddress($email, ['full_name' => $name]);
            } else {
                $this->messageBuilder->setFromAddress($name);
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getReplyTo()
    {
        return @$this->messageBuilder->getMessage()['h:reply-to'];
    }

    /**
     * @inheritdoc
     */
    public function setReplyTo($replyTo)
    {
        if (!is_array($replyTo)) {
            $replyTo = explode(', ', $replyTo);
        }

        foreach ($replyTo as $email => $name) {
            if(is_string($email)) {
                $this->messageBuilder->setReplyToAddress($email, ['full_name' => $name]);
            } else {
                $this->messageBuilder->setReplyToAddress($name);
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTo()
    {
        return @$this->messageBuilder->getMessage()['to'];
    }

    /**
     * @inheritdoc
     */
    public function setTo($to)
    {
        if (!is_array($to)) {
            $to = explode(', ', $to);
        }

        foreach ($to as $email => $name) {
            if(is_string($email)) {
                $this->messageBuilder->addToRecipient($email, ['full_name' => $name]);
            } else {
                $this->messageBuilder->addToRecipient($name);
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCc()
    {
        return @$this->messageBuilder->getMessage()['cc'];
    }

    /**
     * @inheritdoc
     */
    public function setCc($cc)
    {
        $this->messageBuilder->addCcRecipient($cc);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBcc()
    {
        return @$this->messageBuilder->getMessage()['bcc'];
    }

    /**
     * @inheritdoc
     */
    public function setBcc($bcc)
    {
        $this->messageBuilder->addBccRecipient($bcc);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSubject()
    {
        return $this->messageBuilder->getMessage()['subject'];
    }

    /**
     * @inheritdoc
     */
    public function setSubject($subject)
    {
        $this->messageBuilder->setSubject($subject);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTextBody($text)
    {
        $this->messageBuilder->setTextBody($text);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setHtmlBody($html)
    {
        $this->messageBuilder->setHtmlBody($html);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function attach($fileName, array $options = [])
    {
        $this->messageBuilder->addAttachment($fileName,
            isset($options['fileName']) ? $options['fileName'] : null);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function attachContent($content, array $options = [])
    {
        throw new NotSupportedException('Content attaching is not supported yet.');
    }

    /**
     * @inheritdoc
     */
    public function embed($fileName, array $options = [])
    {
        throw new NotSupportedException('File embedding is not supported yet.');
    }

    /**
     * @inheritdoc
     */
    public function embedContent($content, array $options = [])
    {
        throw new NotSupportedException('Content embedding is not supported yet.');
    }

    /**
     * @inheritdoc
     */
    public function toString()
    {
        return json_encode($this->messageBuilder->getMessage(), JSON_PRETTY_PRINT);
    }

    public function addTags($tags)
    {
        foreach ($tags as $tag) {
            $this->messageBuilder->addTag($tag);
        }
        return $this;
    }

    /**
     * Set click tracking
     * @param Boolean|String $enabled true, false, "html"
     * @return $this
     */
    public function setClickTracking($enabled)
    {
        $this->messageBuilder->setClickTracking($enabled);
        return $this;
    }
}