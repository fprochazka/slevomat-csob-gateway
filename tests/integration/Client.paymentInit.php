<?php

/**
 * Client: payment/init
 *
 * @testCase
 */

namespace SlevomatCsobGateway;

use SlevomatCsobGateway\Api\HttpMethod;
use SlevomatCsobGateway\Call\PaymentStatus;
use SlevomatCsobGateway\Call\PayMethod;
use SlevomatCsobGateway\Call\PayOperation;

class ClientPaymentInitTest extends CsobTestCase
{

	public function testPaymentInit()
	{
		$cart = new Cart(new Currency(Currency::CZK));
		$cart->addItem('Purchase', 1, 39000);
		$response = $this->requestFactory->createInitPayment(
			'123',
			new PayOperation(PayOperation::PAYMENT),
			new PayMethod(PayMethod::CARD),
			true,
			'https://slevomat.cz/receive-response',
			new HttpMethod(HttpMethod::POST),
			$cart,
			'Payment',
			null,
			null,
			new Language(Language::CZ)
		)->send($this->client);

		self::assertNotNull($response->getPayId());
		self::assertSame(0, $response->getResultCode()->getValue());
		self::assertSame('OK', $response->getResultMessage());
		self::assertSame(PaymentStatus::S1_CREATED, $response->getPaymentStatus()->getValue());
	}

}
