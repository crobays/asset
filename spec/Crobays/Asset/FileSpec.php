<?php namespace spec\Crobays\Asset;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Crobays\Asset\File');
    }
}
