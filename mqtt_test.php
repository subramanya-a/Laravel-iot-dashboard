<?php

require('vendor/autoload.php');

use \PhpMqtt\Client\MqttClient;
use \PhpMqtt\Client\ConnectionSettings;

$server   = 'broker.mqttdashboard.com';
$port     = 1883;
$clientId = rand(5, 15);
//$clientId = 'clientId-HwMZ0e51x6';
// $username = 'emqx_user';
// $password = null;
$clean_session = false;
$test_topic ='testtopic/testdata';

$connectionSettings  = new ConnectionSettings();
$connectionSettings
//   ->setUsername($username)
//   ->setPassword(null)
  ->setKeepAliveInterval(60)
  ->setLastWillTopic('emqx/test/last-will')
  ->setLastWillMessage('client disconnect')
  ->setLastWillQualityOfService(1);

$mqtt = new MqttClient($server, $port, $clientId);

$mqtt->connect($connectionSettings, $clean_session);
printf("client connected\n");

$mqtt->subscribe($test_topic, function ($topic, $message) {
    printf("Received message on topic [%s]: %s\n", $topic, $message);
}, 0);

for ($i = 0; $i< 5; $i++) {
  $payload = array(
    'protocol' => 'tcp',
    'date' => date('Y-m-d H:i:s'),
    'url' => 'https://github.com/emqx/MQTT-Client-Examples'
  );
  $mqtt->publish(
    // topic
    $test_topic,
    // payload
    json_encode($payload),
    // qos
    0,
    // retain
    true
  );
  printf("msg $i send\n");
  sleep(1);
}

$mqtt->loop(true, true);
//printf("out of the loop");

// use PhpMqtt\Client\Facades\MQTT;

// /** @var \PhpMqtt\Client\Contracts\MqttClient $mqtt */
// $mqtt = MQTT::connection();
// // $mqtt->publish('testtopic/testdata', 'foo', 1);
// // $mqtt->publish('testtopic/testdata', 'bar', 2, true); // Retain the message
// $mqtt->subscribe('testtopic/testdata', function (string $topic, string $message) {
//     echo sprintf('Received QoS level 1 message on topic [%s]: %s', $topic, $message);
// }, 1);
// $mqtt->loop(true,true);

// https://github.com/php-mqtt/client
// http://www.hivemq.com/demos/websocket-client/
// $server   = 'broker.mqttdashboard.com';
// $port     = 1883;
// $clientId = 'clientId-HwMZ0e51x6';

// $mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId);
// $mqtt->connect();
// $mqtt->publish('testtopic/testdata', 'Hello World!', 0);
// $mqtt->disconnect();