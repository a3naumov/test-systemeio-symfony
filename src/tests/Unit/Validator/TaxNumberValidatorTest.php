<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Validator\TaxNumberValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

/**
 * @covers \App\Validator\TaxNumberValidator
 */
class TaxNumberValidatorTest extends TestCase
{
    private TaxNumberValidator $subject;
    private MockObject|ExecutionContextInterface $contextMock;
    private MockObject|Constraint $constraintMock;
    private MockObject|ConstraintViolationBuilderInterface $constraintViolationBuilderMock;

    /**
     * @dataProvider taxNumbersProvider
     */
    public function testValidate(?string $taxNumber, bool $expected): void
    {
        $this->constraintMock->expects(self::any())
            ->method('__get')
            ->with('message')
            ->willReturn('The tax number {{ value }} is not valid.');

        if (!$expected) {
            $this->contextMock
                ->expects(self::once())
                ->method('buildViolation')
                ->with('The tax number {{ value }} is not valid.')
                ->willReturn($this->constraintViolationBuilderMock);

            $this->constraintViolationBuilderMock
                ->expects(self::once())
                ->method('setParameter')
                ->with('{{ value }}', $taxNumber)
                ->willReturnSelf();

            $this->constraintViolationBuilderMock
                ->expects(self::once())
                ->method('addViolation');
        } else {
            $this->contextMock
                ->expects(self::never())
                ->method('buildViolation');
        }

        $this->subject->validate($taxNumber, $this->constraintMock);
    }

    public function taxNumbersProvider(): array
    {
        return [
            [null, true],
            ['', true],

            ['DE123456789', true],
            ['DE1234567811', false],
            ['DE12345678X', false],
            ['DE12345678', false],

            ['IT12345678901', true],
            ['IT123456789012', false],
            ['IT1234567890X', false],
            ['IT1234567890', false],

            ['GR123456789', true],
            ['GR1234567891', false],
            ['GR12345678X', false],
            ['GR12345678', false],

            ['FRPR123456789', true],
            ['FRLI123456789', true],
            ['FRPR1234567891', false],
            ['FRPR12345678X', false],
            ['FRPR12345678', false],
            ['FR12PR3456789', false],

            ['UK123456789', false],
        ];
    }

    protected function setUp(): void
    {
        $this->constraintMock = $this->createMock(Constraint::class);
        $this->contextMock = $this->createMock(ExecutionContextInterface::class);
        $this->constraintViolationBuilderMock = $this->createMock(ConstraintViolationBuilderInterface::class);

        $this->subject = new TaxNumberValidator();
        $this->subject->initialize($this->contextMock);
    }
}