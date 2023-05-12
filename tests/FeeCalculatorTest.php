<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Tests;

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Service\Fee\FeeCalculator;
use InvalidArgumentException;

final class FeeCalculatorTest extends TestCase
{
    public function testGetsFeeIfAmountIsValid(): void
    {
        $calculator = new FeeCalculator();
        $application = new LoanProposal(12, 2100);

        self::assertEquals(90, $calculator->calculate($application));
    }

    public function testGetsFeeIfAmountIsTwoDecimalPoint(): void
    {
        $calculator = new FeeCalculator();
        $application = new LoanProposal(12, 2100.44);

        self::assertEqualsWithDelta(94.56, $calculator->calculate($application),0.00000001);
    }

    public function testGetsFeeIfTermIsInvalid(): void
    {
        $calculator = new FeeCalculator();
        $application = new LoanProposal(99, 2100);

        try {
            $calculator->calculate($application);
        } catch (InvalidArgumentException $e) {
            $this->assertEquals($e->getMessage(), 'Invalid Term Value');
        }
    }

    public function testGetsFeeIfAmountIsInvalid(): void
    {
        $calculator = new FeeCalculator();
        $application = new LoanProposal(24, 9999999);

        try {
            $calculator->calculate($application);
        } catch (InvalidArgumentException $e) {
            $this->assertEquals($e->getMessage(), 'Invalid Amount Value');
        }
    }
}
