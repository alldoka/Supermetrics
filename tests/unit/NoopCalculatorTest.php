<?php

declare(strict_types=1);

namespace Tests\unit;

use DateTime;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use SocialPost\Dto\SocialPostTo;
use Statistics\Calculator\NoopCalculator;
use Statistics\Dto\ParamsTo;
use Statistics\Dto\StatisticsTo;

/**
 * Class ATestTest
 *
 * @covers NoopCalculator
 * @package Tests\unit
 */
class NoopCalculatorTest extends TestCase
{

    /** Id constants could be moved to a reusable enum */
    private const USER_1 = 'user_1';

    private const SEPTEMBER_01 = '2022-09-01 10:10:10';
    private const OCTOBER_01 = '2022-10-01 00:00:00';
    private const OCTOBER_10 = '2022-10-10 10:10:10';
    private const OCTOBER_20 = '2022-10-10 10:10:10';
    private const NOVEMBER_01 = '2022-11-01 00:00:00';

    /**
     * @return NoopCalculator
     * @throws ReflectionException
     */
    public function testAccumulate(): NoopCalculator
    {
        $calculator = $this->mockCalculator();

        $post = (new SocialPostTo())->setAuthorId(authorId: self::USER_1)->setDate(new DateTime(datetime: self::OCTOBER_10));
        $calculator->accumulateData($post);

        $usersCount = new ReflectionProperty(class: $calculator, property: 'usersCount');
        $usersCount->setAccessible(accessible: true);
        self::assertEquals(expected: 1, actual: $usersCount->getValue($calculator));

        $postsCount = new ReflectionProperty(class: $calculator, property: 'postsCount');
        $postsCount->setAccessible(accessible: true);
        self::assertEquals(expected: 1, actual: $postsCount->getValue($calculator));

        return $calculator;
    }


    /**
     * @param NoopCalculator $calculator
     * @return void
     * @depends testAccumulate
     * @throws ReflectionException
     */
    public function testCalculate(NoopCalculator $calculator): void
    {
        $reflection = new ReflectionClass(objectOrClass: $calculator);

        $calculateMethod = $reflection->getMethod(name: 'doCalculate');
        $calculateMethod->setAccessible(accessible: true);

        /** @var StatisticsTo $result */
        $result = $calculateMethod->invokeArgs($calculator, []);
        self::assertEquals(expected: 1, actual: $result->getValue());
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testCalculateEmptyData(): void
    {
        $reflection = new ReflectionClass(objectOrClass: NoopCalculator::class);

        $calculateMethod = $reflection->getMethod(name: 'doCalculate');
        $calculateMethod->setAccessible(accessible: true);

        /** @var StatisticsTo $result */
        $result = $calculateMethod->invokeArgs(new NoopCalculator(), []);
        self::assertEquals(expected: 0, actual: $result->getValue());
    }

    /**
     * @return \array[][]
     */
    public static function twoMonthsDataProvider(): array
    {
        $userOneOctoberTen   = (new SocialPostTo())->setAuthorId(authorId: self::USER_1)->setDate(new DateTime(datetime: self::OCTOBER_10));
        $userOneOctoberOne   = (new SocialPostTo())->setAuthorId(authorId: self::USER_1)->setDate(new DateTime(datetime: self::OCTOBER_20));
        $userOneSeptemberOne = (new SocialPostTo())->setAuthorId(authorId: self::USER_1)->setDate(new DateTime(datetime: self::SEPTEMBER_01));
        return ['two months' => [[$userOneOctoberTen, $userOneOctoberOne, $userOneSeptemberOne]]];
    }

    /**
     * @param array $posts
     * @return void
     * @dataProvider twoMonthsDataProvider
     * @throws ReflectionException
     */
    public function testTwoMonths(array $posts): void
    {
        // IMPORTANT Abstract calculator should be refactored. The current version of code is quite hard to mock up
        // The next code will be simplified after Abstract calculator refactoring.

        $calculator = $this->mockCalculator();

        foreach ($posts as $post) {
            $calculator->accumulateData($post);
        }

        $usersCount = new ReflectionProperty(class: $calculator, property: 'usersCount');
        $usersCount->setAccessible(accessible: true);
        self::assertEquals(expected: 1, actual: $usersCount->getValue($calculator));

        $postsCount = new ReflectionProperty(class: $calculator, property: 'postsCount');
        $postsCount->setAccessible(accessible: true);
        self::assertEquals(expected: 2, actual: $postsCount->getValue($calculator));
    }

    /**
     * @return NoopCalculator
     */
    private function mockCalculator(): NoopCalculator
    {
        $parameter = $this->createMock(originalClassName: ParamsTo::class);
        $parameter->method('getStartDate')
            ->willReturn(new DateTime(datetime: self::OCTOBER_01));
        $parameter->method('getEndDate')
            ->willReturn(new DateTime(datetime: self::NOVEMBER_01));

        $calculator = new NoopCalculator();
        $calculator->setParameters($parameter);

        return $calculator;
    }

}
