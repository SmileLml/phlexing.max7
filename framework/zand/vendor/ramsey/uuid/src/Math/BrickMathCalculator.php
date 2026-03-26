<?php

namespace Ramsey\Uuid\Math;

use Brick\Math\BigDecimal;
use Brick\Math\BigInteger;
use Brick\Math\Exception\MathException;
use Brick\Math\RoundingMode as BrickMathRounding;
use Ramsey\Uuid\Exception\InvalidArgumentException;
use Ramsey\Uuid\Type\Decimal;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Type\Integer as IntegerObject;
use Ramsey\Uuid\Type\NumberInterface;

/**
 * A calculator using the brick/math library for arbitrary-precision arithmetic
 *
 * @psalm-immutable
 */
final class BrickMathCalculator implements CalculatorInterface
{
    private const ROUNDING_MODE_MAP = [
        RoundingMode::UNNECESSARY => BrickMathRounding::UNNECESSARY,
        RoundingMode::UP => BrickMathRounding::UP,
        RoundingMode::DOWN => BrickMathRounding::DOWN,
        RoundingMode::CEILING => BrickMathRounding::CEILING,
        RoundingMode::FLOOR => BrickMathRounding::FLOOR,
        RoundingMode::HALF_UP => BrickMathRounding::HALF_UP,
        RoundingMode::HALF_DOWN => BrickMathRounding::HALF_DOWN,
        RoundingMode::HALF_CEILING => BrickMathRounding::HALF_CEILING,
        RoundingMode::HALF_FLOOR => BrickMathRounding::HALF_FLOOR,
        RoundingMode::HALF_EVEN => BrickMathRounding::HALF_EVEN,
    ];

    /**
     * @param \Ramsey\Uuid\Type\NumberInterface $augend
     * @param \Ramsey\Uuid\Type\NumberInterface ...$addends
     */
    public function add($augend, ...$addends)
    {
        $sum = BigInteger::of($augend->toString());

        foreach ($addends as $addend) {
            $sum = $sum->plus($addend->toString());
        }

        return new IntegerObject((string) $sum);
    }

    /**
     * @param \Ramsey\Uuid\Type\NumberInterface $minuend
     * @param \Ramsey\Uuid\Type\NumberInterface ...$subtrahends
     */
    public function subtract($minuend, ...$subtrahends)
    {
        $difference = BigInteger::of($minuend->toString());

        foreach ($subtrahends as $subtrahend) {
            $difference = $difference->minus($subtrahend->toString());
        }

        return new IntegerObject((string) $difference);
    }

    /**
     * @param \Ramsey\Uuid\Type\NumberInterface $multiplicand
     * @param \Ramsey\Uuid\Type\NumberInterface ...$multipliers
     */
    public function multiply($multiplicand, ...$multipliers)
    {
        $product = BigInteger::of($multiplicand->toString());

        foreach ($multipliers as $multiplier) {
            $product = $product->multipliedBy($multiplier->toString());
        }

        return new IntegerObject((string) $product);
    }

    /**
     * @param int $roundingMode
     * @param int $scale
     * @param \Ramsey\Uuid\Type\NumberInterface $dividend
     * @param \Ramsey\Uuid\Type\NumberInterface ...$divisors
     */
    public function divide(
        $roundingMode,
        $scale,
        $dividend,
        ...$divisors
    ) {
        $brickRounding = $this->getBrickRoundingMode($roundingMode);

        $quotient = BigDecimal::of($dividend->toString());

        foreach ($divisors as $divisor) {
            $quotient = $quotient->dividedBy($divisor->toString(), $scale, $brickRounding);
        }

        if ($scale === 0) {
            return new IntegerObject((string) $quotient->toBigInteger());
        }

        return new Decimal((string) $quotient);
    }

    /**
     * @param string $value
     * @param int $base
     */
    public function fromBase($value, $base)
    {
        try {
            return new IntegerObject((string) BigInteger::fromBase($value, $base));
        } catch (MathException | \InvalidArgumentException $exception) {
            throw new InvalidArgumentException(
                $exception->getMessage(),
                (int) $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param IntegerObject $value
     * @param int $base
     */
    public function toBase($value, $base)
    {
        try {
            return BigInteger::of($value->toString())->toBase($base);
        } catch (MathException | \InvalidArgumentException $exception) {
            throw new InvalidArgumentException(
                $exception->getMessage(),
                (int) $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param IntegerObject $value
     */
    public function toHexadecimal($value)
    {
        return new Hexadecimal($this->toBase($value, 16));
    }

    /**
     * @param \Ramsey\Uuid\Type\Hexadecimal $value
     */
    public function toInteger($value)
    {
        return $this->fromBase($value->toString(), 16);
    }

    /**
     * Maps ramsey/uuid rounding modes to those used by brick/math
     * @param int $roundingMode
     */
    private function getBrickRoundingMode($roundingMode)
    {
        return self::ROUNDING_MODE_MAP[$roundingMode] ?? 0;
    }
}
