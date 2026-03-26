<?php

namespace Ramsey\Uuid\Converter\Number;

use Ramsey\Uuid\Converter\NumberConverterInterface;
use Ramsey\Uuid\Math\BrickMathCalculator;

/**
 * Previously used to integrate moontoast/math as a bignum arithmetic library,
 * BigNumberConverter is deprecated in favor of GenericNumberConverter
 *
 * @deprecated Transition to {@see GenericNumberConverter}.
 *
 * @psalm-immutable
 */
class BigNumberConverter implements NumberConverterInterface
{
    /**
     * @var \Ramsey\Uuid\Converter\NumberConverterInterface
     */
    private $converter;

    public function __construct()
    {
        $this->converter = new GenericNumberConverter(new BrickMathCalculator());
    }

    /**
     * @inheritDoc
     * @psalm-pure
     * @param string $hex
     */
    public function fromHex($hex)
    {
        return $this->converter->fromHex($hex);
    }

    /**
     * @inheritDoc
     * @psalm-pure
     * @param string $number
     */
    public function toHex($number)
    {
        return $this->converter->toHex($number);
    }
}
