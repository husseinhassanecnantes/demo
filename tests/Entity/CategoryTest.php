<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testNameGetterReturnCorrectValue(): void
    {
        $category = new Category();
        $category->setName('pouf');

        $this->assertEquals('pouf', $category->getName());
    }
}
