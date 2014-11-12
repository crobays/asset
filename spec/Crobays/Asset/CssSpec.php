<?php namespace spec\Crobays\Asset;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CssSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Crobays\Asset\Css');
    }

    function it_can_create_a_css_url()
    {
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'css' => '/style.css',
        // ]);
     	$this->url()->shouldBe('//assets.example.com/style.css');
    }

   	function it_can_create_a_css_with_directory_url()
    {
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'css' => 'styles/style.css',
        // ]);
        $this->setUri('styles/style.css');
     	$this->url()->shouldBe('//assets.example.com/styles/style.css');
    }

   	function it_can_create_a_custom_css_url()
    {
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'css' => 'style.css',
        // ]);
        $this->setUri('old-style.css');
     	$this->url()->shouldBe('//assets.example.com/old-style.css');
    }
}
