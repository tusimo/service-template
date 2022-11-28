<?php

declare(strict_types=1);
/**
 * This file is part of API Service.
 *
 * Please follow the code rules : PSR-2
 */
use Hyperf\Kafka\Constants\KafkaStrategy;

return [
    'default' => [
        'connect_timeout' => (float) env('KAFKA_CONNECT_TIMEOUT', -1),
        'send_timeout' => (float) env('KAFKA_SEND_TIMEOUT', -1),
        'recv_timeout' => (float) env('KAFKA_RECV_TIMEOUT', -1),
        'client_id' => env('KAFKA_CLIENT_ID', ''),
        'max_write_attempts' => (int) env('KAFKA_MAX_WRITE_ATTEMPTS', 3),
        'bootstrap_servers' => explode(',', env('KAFKA_BOOTSTRAP_SERVERS', '')),
        'acks' => (int) env('KAFKA_ACKS', -1),
        'producer_id' => -1,
        'producer_epoch' => -1,
        'partition_leader_epoch' => -1,
        'interval' => env('KAFKA_INTERVAL', 0.01),
        'session_timeout' => 60,
        'rebalance_timeout' => 60,
        'replica_id' => -1,
        'rack_id' => '',
        'group_retry' => 5,
        'group_retry_sleep' => 1,
        'group_heartbeat' => 3,
        'offset_retry' => 5,
        'auto_create_topic' => true,
        'partition_assignment_strategy' => KafkaStrategy::RANGE_ASSIGNOR,
        'sasl' => [
        ],
        'ssl' => [
        ],
        'pool' => [
            'min_connections' => (int) env('KAFKA_POOL_MIN_CONNECTIONS', 1),
            'max_connections' => (int) env('KAFKA_POOL_MAX_CONNECTIONS', 10),
            'connect_timeout' => (int) env('KAFKA_POOL_CONNECT_TIMEOUT', 10.0),
            'wait_timeout' => (float) env('KAFKA_POOL_WAIT_TIMEOUT', 3.0),
            'heartbeat' => (float) env('KAFKA_POOL_HEARTBEAT', -1),
            'max_idle_time' => (float) env('KAFKA_POOL_MAX_IDLE_TIME', 60.0),
        ],
    ],
];
