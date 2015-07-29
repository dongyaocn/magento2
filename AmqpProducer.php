<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Framework\Amqp;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Magento\Framework\Amqp\RabbitMqConfig;
use Magento\Framework\Amqp\MessageEncoder;

/**
 * An AMQP Producer to handle publishing a message.
 */
class AmqpProducer implements ProducerInterface
{
    /**
     * @var RabbitMqConfig
     */
    private $config;

    public function __construct(
        RabbitMqConfig $config
    ) {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function publish($topicName, $data)
    {
        $exchange = 'magento';

        $connection = new AMQPConnection(
            $this->config->getValue(RabbitMqConfig::HOST),
            $this->config->getValue(RabbitMqConfig::PORT),
            $this->config->getValue(RabbitMqConfig::USERNAME),
            $this->config->getValue(RabbitMqConfig::PASSWORD),
            $this->config->getValue(RabbitMqConfig::VIRTUALHOST)
        );
        $channel = $connection->channel();
        $channel->queue_declare($topicName, false, true, false, false);
        $channel->exchange_declare($exchange, 'direct', false, true, false);
        $channel->queue_bind($topicName, $exchange);

        $msg = new AMQPMessage($data, array('content_type' => 'text/plain', 'delivery_mode' => 2));
        $channel->basic_publish($msg, $exchange);

        $channel->close();
        $connection->close();
    }
}
