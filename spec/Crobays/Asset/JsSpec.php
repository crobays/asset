<?php namespace spec\Crobays\Asset;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Crobays\Asset\Js');
    }

    function it_can_create_a_js_url()
    {
        $this->beConstructedWith([
            'url' => 'assets.example.com',
            'js' => '/script.js',
        ]);
        $this->getUrl()->shouldBe('//assets.example.com/script.js');
    }

   	function it_can_create_a_js_with_directory_url()
    {
        $this->beConstructedWith([
            'url' => 'assets.example.com',
            'js' => '/script.js',
        ]);
        $this->setUri('/scripts/script.js');
        $this->getUrl()->shouldBe('//assets.example.com/scripts/script.js');
    }
    
   	function it_can_create_a_custom_js_url()
    {
        $this->beConstructedWith([
            'url' => 'assets.example.com',
            'js' => '/script.js',
        ]);
        $this->setUri('old-script.js');
        $this->getUrl()->shouldBe('//assets.example.com/old-script.js');
    }
}
