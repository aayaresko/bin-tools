<?php

namespace App\Validator\Constraints;

use App\Dto\Trading\ProfitabilityDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsProfitabilityDroValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsProfitabilityDro) {
            throw new UnexpectedTypeException($constraint, ContainsProfitabilityDro::class);
        }

        if (!$value instanceof ProfitabilityDto) {
            throw new UnexpectedValueException($value, ProfitabilityDto::class);
        }

        if (30 <= $value->numberOfBetsPerDay * $value->betSizeInPercentage) {
            $this->context
                ->buildViolation($constraint->betSizeIsToBigMessage)
                ->setParameter('{{betsPerDay}}', $value->numberOfBetsPerDay)
                ->setParameter('{{betSize}}', $value->betSizeInPercentage)
                ->atPath('betSizeInPercentage')
                ->addViolation();
        }

        if ($value->profitableBetsPercentage < 60) {
            $this->context
                ->buildViolation($constraint->tradingIsNotProfitableMessage)
                ->setParameter('{{profitability}}', $value->profitableBetsPercentage)
                ->atPath('profitableBetsPercentage')
                ->addViolation();
        }
    }
}