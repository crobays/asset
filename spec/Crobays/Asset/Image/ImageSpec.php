<?php namespace spec\Crobays\Asset\Image;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ImageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Crobays\Asset\Image\Image');
    }

    function it_can_create_an_image_url()
    {
        $this->beConstructedWith([
            'url' => 'assets.example.com',
            'root-path' => __DIR__.'/../../../test-images',
            'images-directories' => ['image' => ['source' => 'images', 'uri' => 'img']],
        ]);
        $this->setUri('test-image.jpg');
        $this->getUrl()->shouldBe('//assets.example.com/img/test-image.jpg');
    }

    function it_accepts_svg()
    {
        $this->beConstructedWith([
            'url' => 'assets.example.com',
            'root-path' => __DIR__.'/../../../test-images',
            'images-directories' => ['image' => ['source' => 'images', 'uri' => 'img']],
        ]);
        $this->setUri('trollface.svg');
        $this->getUri()->shouldBe('img/trollface.svg');
        $this->getUrl()->shouldBe('//assets.example.com/img/trollface.svg');
        $this->getFileName()->shouldBe('trollface.svg');
        $this->getFileNameBase()->shouldBe('trollface');
        $this->getExtension()->shouldBe('svg');
    }

    function it_can_have_dots_in_file_name()
    {
        $this->beConstructedWith([
            'url' => 'assets.example.com',
            'root-path' => __DIR__.'/../../../test-images',
            'images-directories' => ['image' => ['source' => 'images', 'uri' => 'img']],
        ]);
        $this->setUri('sub-directory/test.image.png');
        $this->getUri()->shouldBe('img/sub-directory/test.image.png');
        $this->getFileName()->shouldBe('test.image.png');
        $this->getFileNameBase()->shouldBe('test.image');
        $this->getExtension()->shouldBe('png');
    }

    function it_fetches_root_path_with_double_slashes()
    {
    	$this->beConstructedWith(['root-path' => '/project//assets']);
    	$this->getRootPath()->shouldBe('/project/assets');
    }

    function it_fetches_root_path_with_trailing_slash()
    {
    	$this->beConstructedWith(['root-path' => '/project/assets/']);
    	$this->getRootPath()->shouldBe('/project/assets');
    }

    function it_accepts_img_uri_with_no_param()
    {
    	$this->beConstructedWith([
            'url' => 'assets.example.com',
            'root-path' => __DIR__.'/../../../test-images',
            'images-directories' => ['image' => ['source' => 'images', 'uri' => 'img']],
            'arguments-seperator' => '___',
            'argument-seperator' => '-',
        ]);
    	$this->setUri('test-image.jpg');
    	$this->getExtension()->shouldBe('jpg');
        $this->getUri()->shouldBe('img/test-image.jpg');
        $this->getUrl()->shouldBe('//assets.example.com/img/test-image.jpg');
    	$this->getSourcePath()->shouldBe(__DIR__.'/../../../test-images/images/test-image.jpg');
    }

}
