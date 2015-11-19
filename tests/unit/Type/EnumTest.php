<?php

namespace SlevomatCsobGateway\Type;

class EnumTest extends \PHPUnit_Framework_TestCase
{

	public function testEqualsInstances()
	{
		$review1 = new StatusEnum(StatusEnum::REVIEW);
		$review2 = new StatusEnum(StatusEnum::REVIEW);

		$this->assertEquals($review1, $review2);
	}

	public function testDifferentInstances()
	{
		$review = new StatusEnum(StatusEnum::REVIEW);
		$draft = new StatusEnum(StatusEnum::DRAFT);

		$this->assertNotEquals($review, $draft);
	}

	public function testGetValue()
	{
		$review = new StatusEnum(StatusEnum::REVIEW);

		$this->assertSame(StatusEnum::REVIEW, $review->getValue());
	}

	public function testEqualsValue()
	{
		$review = new StatusEnum(StatusEnum::REVIEW);
		$draft = new StatusEnum(StatusEnum::DRAFT);

		$this->assertTrue($review->equalsValue(StatusEnum::REVIEW));
		$this->assertFalse($draft->equalsValue(StatusEnum::REVIEW));
	}

	public function testEquals()
	{
		$review1 = new StatusEnum(StatusEnum::REVIEW);
		$review2 = new StatusEnum(StatusEnum::REVIEW);
		$draft = new StatusEnum(StatusEnum::DRAFT);

		$this->assertTrue($review1->equals($review2));
		$this->assertFalse($review1->equals($draft));

		try {
			$admin = new RoleEnum(RoleEnum::ADMIN);
			$review1->equals($admin);
			$this->fail();

		} catch (InvalidEnumTypeException $e) {
			$this->assertSame($admin, $e->getEnum());
			$this->assertSame(get_class($review1), $e->getExpectedClass());
		}
	}

	public function testInvalidEnumValue()
	{
		try {
			new StatusEnum(10);
			$this->fail();

		} catch (InvalidEnumValueException $e) {
			$this->assertSame(10, $e->getValue());
			$this->assertEquals([
				'DRAFT' => StatusEnum::DRAFT,
				'REVIEW' => StatusEnum::REVIEW,
				'PUBLISHED' => StatusEnum::PUBLISHED,
			], $e->getAvailableValues());
		}
	}

}