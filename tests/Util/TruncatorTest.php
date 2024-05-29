<?php

namespace App\Tests\Util;

use App\Util\Truncator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TruncatorTest extends KernelTestCase
{
    private Truncator $truncator;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();

        /** @var Truncator $truncator */
        $this->truncator = $container->get(Truncator::class);
    }

    public function testReducedTrunctorWithTextMoreTenCaracters(): void
    {
        $this->assertSame('abcdefg...', $this->truncator->reduce("abcdefghiklmnopqrstuwxyz"));

    }

    public function testReducedTrunctorWithTextLessTenCaracters(): void
    {
        $this->assertSame('abcdef', $this->truncator->reduce("abcdef"));

    }

    public function testReducedTrunctorWithTextEqualTenCaracters(): void
    {
        $this->assertSame('abcdefghik', $this->truncator->reduce("abcdefghik"));

    }
}
