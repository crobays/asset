<?php namespace spec\Crobays\Asset\Image;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ImageManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Crobays\Asset\Image\ImageManager');
    }

    // function it_fetches_root_path_with_double_slashes()
    // {
    // 	$this->configure(['root-path' => '/project//assets']);
    // 	$this->getImage()->getRootPath()->shouldBe('/project/assets');
    // }

    // function it_fetches_root_path_with_trailing_slash()
    // {
    // 	$this->configure(['root-path' => '/project/assets/']);
    // 	$this->getImage()->getRootPath()->shouldBe('/project/assets');
    // }

    // function it_accepts_uri_with_no_param()
    // {
    // 	$this->configure(['root-path' => '../../']);
    // 	$this->get('/img/test-image.jpg');
    // 	$this->getImage()->getExtension()->shouldBe('jpg');
    // 	$this->getImage()->getPath()->shouldBe('../../img/img/test-image.jpg');
    // 	$this->getImage()->getOriginalPath()->shouldBe('../../images/test-image.jpg');
    // }

    // function it_accepts_uri_with_dot_in_file_name()
    // {
    // 	$this->configure(['root-path' => '../../']);
    // 	$this->get('/images/test.image.jpg');
    // 	$this->getImage()->getExtension()->shouldBe('jpg');
    // 	$this->getImage()->getOriginalPath()->shouldBe('../../images/test.image.jpg');
    // }

    // function it_accepts_uri_with_tree_underscores_as_part_of_the_file_name()
    // {
    // 	$this->configure(['root-path' => '../../']);
    // 	$this->get('/images/test___image___w200.jpg');
    // 	$this->getImage()->getExtension()->shouldBe('jpg');
    // 	$this->getImage()->getPath()->shouldBe('../../img/test___image___w200.jpg');
    // 	$this->getImage()->getOriginalPath()->shouldBe('../../images/test___image.jpg');
    // }

    // function it_accepts_uri_with_width_200_param()
    // {
    // 	$this->configure(['root-path' => '../../']);
    // 	$this->get('/img/test-image___w200.jpg');
    // 	$this->getImage()->getOriginalPath()->shouldBe('../../images/test-image.jpg');
    // 	$this->getImage()->getWidth()->shouldBe(200);
    // 	$this->getImage()->getExtension()->shouldBe('jpg');
    // }

    // function it_accepts_uri_with_all_params()
    // {
    // 	$this->configure(['root-path' => '../../']);
    // 	$this->get('/img/test-image___w100-h110-cw120-ch130-cx140-cy150.jpg');
    // 	$this->getImage()->getOriginalPath()->shouldBe('../../images/test-image.jpg');
    // 	$this->getImage()->getWidth()->shouldBe(100);
    // 	$this->getImage()->getHeight()->shouldBe(110);
    // 	$this->getImage()->getCropWidth()->shouldBe(120);
    // 	$this->getImage()->getCropHeight()->shouldBe(130);
    // 	$this->getImage()->getCropX()->shouldBe(140);
    // 	$this->getImage()->getCropY()->shouldBe(150);
    // 	$this->getImage()->getExtension()->shouldBe('jpg');
    // }
}
