<?php

namespace Rpc;

error_reporting(0);

use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

class RpcClient 
{
	private $client;
	private $transport;

	public function __construct($host, $port, $path)
	{
		$GEN_DIR = realpath(dirname(__FILE__)).'/gen-php';

		$loader = new ThriftClassLoader();
		$loader->registerDefinition('tutorial', $GEN_DIR);
		$loader->register();

		try {

			$socket = new THttpClient($host, $port, $path);

			$transport = new TBufferedTransport($socket, 1024, 1024);
			$protocol = new TBinaryProtocol($transport);
			$client = new \tutorial\DemoClient($protocol);

			$this->client = $client;
			$this->transport = $transport;

		} catch (TException $tx) {
		    print 'TException: '.$tx->getMessage()."\n";
		}
	}

	public function __call($name, $arguments)
	{

		$this->transport->open();

		$respose = call_user_func_array(array($this->client, $name), $arguments);

		$this->transport->close();

		return $respose;
	}	
}


?>


