<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Service\Fee;

use PragmaGoTech\Interview\Model\LoanProposal;

class FeeCalculator implements FeeCalculatorInterface
{
    public function calculate(LoanProposal $application): float
    {
        $this->validateLoanProposal($application);
        $feeStructure = $this->getFeeStructure($application);
        $amount = $application->getAmount();

        return $this->calculateLinearInterpolation($feeStructure, $amount);
    }

    private function validateLoanProposal(LoanProposal $application): bool
    {
        if (false === FeeStructure::isTermValid($application->getTerm())) {
            return false;
        }

        if (FeeStructure::isValueValid($application->getAmount())) {
            return false;
        }

        return true;
    }

    private function getFeeStructure(LoanProposal $application): array
    {
        return FeeStructure::getStructure($application->getTerm());
    }

    private function calculateLinearInterpolation(array $feeStructure, float $amount): float
    {
        if (true === array_key_exists($amount, $feeStructure)) {
            return $feeStructure[$amount];
        }

        $minTermValue = null;
        $maxTermValue = null;

        foreach ($feeStructure as $termKey => $fee) {
            if (($amount < $termKey) && ((null === $minTermValue) || ($termKey < $minTermValue))) {
                $minTermValue = $termKey;
            }

            if (($termKey < $amount) && ((null === $maxTermValue) || ($maxTermValue < $termKey))) {
                $maxTermValue = $termKey;
            }
        }

        $base = $feeStructure[$minTermValue];
        $value = (($amount - $minTermValue) * ($feeStructure[$maxTermValue] - $feeStructure[$minTermValue])) / ($maxTermValue - $minTermValue);

        $fee = $base + $value;
        $total = $fee + $amount;

        $total = ceil($total / 5) * 5;

        return round($total - $amount, 2);
    }
}
