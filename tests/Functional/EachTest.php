<?php

/**
 * @package   Functional-php
 * @author    Lars Strojny <lstrojny@php.net>
 * @copyright 2011-2017 Lars Strojny
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/lstrojny/functional-php
 */

namespace Functional\Tests;

use ArrayIterator;

use function Functional\each;

class EachTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->cb = $this->getMockBuilder('cb')
                         ->setMethods(['call'])
                         ->getMock();


        $this->list = ['value0', 'value1', 'value2', 'value3'];
        $this->listIterator = new ArrayIterator($this->list);
        $this->hash = ['k0' => 'value0', 'k1' => 'value1', 'k2' => 'value2'];
        $this->hashIterator = new ArrayIterator($this->hash);
    }

    public function testArray()
    {
        $this->prepareCallback($this->list);
        $this->assertNull(each($this->list, [$this->cb, 'call']));
    }

    public function testIterator()
    {
        $this->prepareCallback($this->listIterator);
        $this->assertNull(each($this->listIterator, [$this->cb, 'call']));
    }

    public function testHash()
    {
        $this->prepareCallback($this->hash);
        $this->assertNull(each($this->hash, [$this->cb, 'call']));
    }

    public function testHashIterator()
    {
        $this->prepareCallback($this->hashIterator);
        $this->assertNull(each($this->hashIterator, [$this->cb, 'call']));
    }

    public function testExceptionIsThrownInArray()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        each($this->list, [$this, 'exception']);
    }

    public function testExceptionIsThrownInCollection()
    {
        $this->expectException('DomainException');
        $this->expectExceptionMessage('Callback exception');
        each($this->listIterator, [$this, 'exception']);
    }

    public function prepareCallback($collection)
    {
        $i = 0;
        foreach ($collection as $key => $value) {
            $this->cb->expects($this->at($i++))->method('call')->with($value, $key, $collection);
        }
    }

    public function testPassNonCallable()
    {
        $this->expectArgumentError("Argument 2 passed to Functional\\each() must be callable");
        each($this->list, 'undefinedFunction');
    }

    public function testPassNoCollection()
    {
        $this->expectArgumentError('Functional\each() expects parameter 1 to be array or instance of Traversable');
        each('invalidCollection', 'strlen');
    }
}
