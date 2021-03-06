<?php declare(strict_types = 1);

namespace SlevomatCsobGateway\Call;

use DateTimeImmutable;
use SlevomatCsobGateway\Api\ApiClient;
use SlevomatCsobGateway\Api\Response;
use SlevomatCsobGateway\Api\ResponseCode;

class EchoRequestTest extends \PHPUnit_Framework_TestCase
{

	public function testSend()
	{
		$apiClient = $this->getMockBuilder(ApiClient::class)
			->disableOriginalConstructor()
			->getMock();

		$apiClient->expects(self::once())->method('get')
			->with('echo/{merchantId}/{dttm}/{signature}', [
				'merchantId' => '012345',
			])
			->willReturn(
				new Response(new ResponseCode(ResponseCode::S200_OK), [
					'dttm' => '20140425131559',
					'resultCode' => 0,
					'resultMessage' => 'OK',
				])
			);

		/** @var ApiClient $apiClient */
		$echoRequest = new EchoRequest(
			'012345'
		);

		$echoResponse = $echoRequest->send($apiClient);

		$this->assertInstanceOf(EchoResponse::class, $echoResponse);
		$this->assertEquals(DateTimeImmutable::createFromFormat('YmdHis', '20140425131559'), $echoResponse->getResponseDateTime());
		$this->assertEquals(new ResultCode(ResultCode::C0_OK), $echoResponse->getResultCode());
		$this->assertSame('OK', $echoResponse->getResultMessage());
	}

}
