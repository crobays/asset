<?php namespace spec\Crobays\Asset\Image;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PictureSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Crobays\Asset\Image\Picture');
    }

   	function it_can_create_an_picture_url()
    {
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'root-path' => __DIR__.'/../../../test-images',
        //     'images-directories' => ['picture' => ['source' => 'pictures', 'uri' => 'pic']],
        // ]);
    	$this->setUri('test.with.dots.image.jpg');
     	$this->url()->shouldBe('//assets.example.com/pic/test.with.dots.image.jpg');
    }
}
