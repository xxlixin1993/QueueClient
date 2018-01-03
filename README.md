# Introduction
A nats or redis queue client in PHP

# Install
```
composer require l-queue/l-client
```

# Basic Usage
```
$client = new \LQueue\Factory();
$natsClient = $client->getQueue('nats');
$natsClient->driver();
$natsClient->publish('FOO', 'bar', 11);
```

# Nats Client
NATS is an open-source, cloud-native messaging system. In addition to functioning as the “nervous system” for the Apcera platform, companies like Baidu, Siemens, VMware, HTC, and Ericsson rely on NATS for its highly performant and resilient messaging capabilities.
## Nats Queue
This library supported publish,request and subscribe function.

### pub
```
$client = new \LQueue\Factory();
$natsClient = $client->getQueue('nats');
$natsClient->driver();
$natsClient->publish('FOO', 'bar', 11);
$natsClient->close();
```

### subscribe
```
$client = new \LQueue\Factory();
$natsClient = $client->getQueue('nats');
$natsClient->driver();
$natsClient->subscribe('FOO', function ($response) {
    printf("Data: %s\r\n", $response->getBody());
});
$natsClient->close();
```

### pubsub
```
$client = new \LQueue\Factory();
$natsClient = $client->getQueue('nats');
$natsClient->driver();
$natsClient->subscribe('FOO', function ($response) {
    printf("Data: %s\r\n", $response->getBody());
});

$natsClient->publish('FOO', 'bar');

// Wait for 1 message.
$natsClient->wait(1);
$natsClient->close();
```

### request
```
$client = new \LQueue\Factory();
$natsClient = $client->getQueue('nats');
// set username and password when you config the nats
$natsClient->getConnectOption()->setUser('derek')->setPass('T0pS3cr3t')->setPort(4242);
$natsClient->driver();
$sid = $natsClient->subscribe(
    'foo',
    function ($response) {
        $response->reply('Reply: Hello, ' . $response->getBody() . ' ^_^!');
    }
);

$natsClient->request(
    'foo',
    'bar',
    function ($response) {
        echo $response->getBody();
    }
);
$natsClient->close();
```

## Nats Option
Configure some parameters

### username and password
```
$natsClient->getConnectOption()->setUser('derek')->setPass('T0pS3cr3t')
```
### host port
```
$natsClient->getConnectOption()->setHost('127.0.0.1')->setPort(4242);
```
### timeout
```
// wait for 10s,then close the connection
$natsClient->getConnectOption()->setTimeout(10)
```
# Redis Queue Client
Just packaging some redis function.
## Redis Queue

### publish
```
$client = new \LQueue\Factory();
$redisClient = $client->getQueue('redis');
$option = $redisClient->getConnectOption()->setPass('123456');
$redisClient->driver();
$redisClient->publish('FOO', 'bar');
$redisClient->close();
```

### Enter the queue use lpush
```
$client = new \LQueue\Factory();
$redisClient = $client->getQueue('redis');
$option = $redisClient->getConnectOption()->setPass('123456');
$redisClient->driver();
$redisClient->enQueue('FOO', 'bar');
$redisClient->close();
```

## Redis Option
Configure some parameters

### password
```
$client = new \LQueue\Factory();
$redisClient = $client->getQueue('redis');
$option = $redisClient->getConnectOption()->setPass('123456');
```
### host port
```
$redisClient->getConnectOption()->setHost('127.0.0.1')->setPort(4242);
```
### timeout
```
// wait for 10s,then close the connection
$redisClient->getConnectOption()->setTimeout(10)
```

# License
MIT