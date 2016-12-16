<?php

namespace rpc;

use app\models\Users;

error_reporting(0);

use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TPhpStream;
use Thrift\Transport\TBufferedTransport;

class RpcServer
{
	public static function run($obj)
	{
		$GEN_DIR = realpath(dirname(__FILE__)).'/gen-php';
		$loader = new ThriftClassLoader();
		$loader->registerDefinition('tutorial', $GEN_DIR);
		$loader->register();

		$handler = $obj;
		$processor = new \tutorial\DemoProcessor($handler);

		$transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
		$protocol = new TBinaryProtocol($transport, true, true);

		$transport->open();
		$processor->process($protocol, $protocol);
		$transport->close();
	}
}




