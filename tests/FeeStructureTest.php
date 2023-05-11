<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Tests;

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Service\Fee\FeeStructure;

final class FeeStructureTest extends TestCase
{
    public function testCanCheckIfValueIsValid(): void
    {
        self::assertTrue(FeeStructure::isValueValid(1000.0));
    }

    public function testCanCheckIfValueIsNotValid(): void
    {
        self::assertFalse(FeeStructure::isValueValid(1001.0));
    }

    public function testCanCheckIsTermValid(): void
    {
        self::assertTrue(FeeStructure::isTermValid(12));
    }

    public function testCanCheckIsTermInvalid(): void
    {
        self::assertFalse(FeeStructure::isTermValid(13));
    }
}