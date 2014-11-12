<?php namespace spec\Crobays\Asset;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Exception\Example\FailureException;

class AssetManagerSpec extends ObjectBehavior
{
    function let(\Illuminate\Config\Repository $config)
    {
        //$config->
        
        $this->beConstructedWith($config);
    }

    function it_is_initializable()
    {
    	// $this->beConstructedWith([
    	// 	'url' => 'assets.example.com'
    	// ]);
        $this->shouldHaveType('Crobays\Asset\AssetManager');
    }

    function it_accepts_with_http_prefix()
    {
    	// $this->beConstructedWith([
    	// 	'url' => 'http://assets.example.com',
     //        'css' => 'style.css',
     //    ]);
        $this->domain()->shouldBe('assets.example.com');
        $this->url()->shouldBe('http://assets.example.com');
    }

    function it_accepts_with_https_prefix()
    {
    	// $this->beConstructedWith([
    	// 	'url' => 'https://assets.example.com',
     //        'css' => 'style.css',
     //    ]);
        $this->domain()->shouldBe('assets.example.com');
        $this->url()->shouldBe('https://assets.example.com');
    }

    function it_accepts_with_double_slashes_prefix()
    {
    	// $this->beConstructedWith([
    	// 	'url' => '//assets.example.com',
     //        'css' => '/style.css',
     //    ]);
        $this->domain()->shouldBe('assets.example.com');
        $this->url()->shouldBe('//assets.example.com');
    }

    function it_can_create_a_css_html_element()
    {
    	// $this->beConstructedWith([
    	// 	'url' => 'assets.example.com',
     //        'css' => '/style.css',
     //    ]);
        $this->css()->shouldBe('<link type="text/css" href="//assets.example.com/style.css" rel="stylesheet">');
    }

    function it_can_create_a_css_html_element_with_custom_css_file()
    {
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'css' => '/style.css',
        // ]);
        $this->css('custom.css')->shouldBe('<link type="text/css" href="//assets.example.com/custom.css" rel="stylesheet">');
    }

    function it_can_create_a_js_html_element()
    {
    	// $this->beConstructedWith([
    	// 	'url' => 'assets.example.com',
     //        'js' => '/script.js',
     //    ]);
        $this->js('custom.js')->shouldBe('<script type="text/javascript" src="//assets.example.com/custom.js"></script>');
    }

    function it_can_create_a_js_html_element_with_custom_css_file()
    {
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'js' => '/script.js',
        // ]);
        $this->js('//platform.twitter.com/widgets.js')->shouldBe('<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>');
    }

    function it_can_create_a_js_html_element_with_custom_external_css_file()
    {
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'js' => '/script.js',
        // ]);
        $this->js()->shouldBe('<script type="text/javascript" src="//assets.example.com/script.js"></script>');
    }

    function it_can_create_a_img_html_element()
    {
    	if(is_file($f = __DIR__.'/../../test-images/img/test-image.jpg'))
    	{
    		unlink($f);
    	}

    	// $this->beConstructedWith([
    	// 	'url' => 'assets.example.com',
    	// 	'root-path' => __DIR__.'/../../test-images',
    	// 	'images-directories' => [
    	// 		'image' => [
    	// 			'source' => 'images',
    	// 			'uri' => 'img',
    	// 		],
    	// 	],
     //    ]);
        $this->img('test-image.jpg')->shouldBe('<img src="//assets.example.com/img/test-image.jpg">');
        
        if( ! is_file($f))
        {
        	throw new FailureException("Generation of image failed");
        }
    }

    function it_can_create_a_svg_html_element()
    {
        if(is_file($f = __DIR__.'/../../test-images/img/trollface.svg'))
        {
            unlink($f);
        }

        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'root-path' => __DIR__.'/../../test-images',
        //     'images-directories' => [
        //         'image' => [
        //             'source' => 'images',
        //             'uri' => 'img',
        //         ],
        //     ],
        // ]);

        $this->img('trollface.svg')->shouldBe('<img src="//assets.example.com/img/trollface.svg">');
        
        if( ! is_file($f))
        {
            throw new FailureException("Generation of image failed");
        }
    }

    function it_can_create_a_svg_html_element_with_width_300()
    {
        if(is_file($f = __DIR__.'/../../test-images/img/trollface.svg'))
        {
            unlink($f);
        }

        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'root-path' => __DIR__.'/../../test-images',
        //     'images-directories' => [
        //         'image' => [
        //             'source' => 'images',
        //             'uri' => 'img',
        //         ],
        //     ],
        // ]);
        
        $this->img('trollface.svg', ['w' => 300])->shouldBe('<img width="300" src="//assets.example.com/img/trollface.svg">');
        
        if( ! is_file($f))
        {
            throw new FailureException("Generation of image failed");
        }
    }

    function it_throws_exception_when_invalid_image_is_given()
    {
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'root-path' => __DIR__.'/../../test-images',
        //     'images-directories' => [
        //         'image' => [
        //             'source' => 'images',
        //             'uri' => 'img',
        //         ],
        //     ],
        // ]);

        $this->shouldThrow('Crobays\Asset\Exception\InvalidImagePathException')
             ->duringImg('non-existing-image.jpg');
    }

    function it_can_create_a_img_html_element_with_width_and_crop_height()
    {
        $f = __DIR__."/../../test-images/img/test-image___w400-ch30.jpg";
        if(is_file($f))
        {
            unlink($f);
        }
    	// $this->beConstructedWith([
    	// 	'url' => 'assets.example.com',
    	// 	'root-path' => __DIR__.'/../../test-images',
    	// 	'images-directories' => [
    	// 		'image' => [
    	// 			'source' => 'images',
    	// 			'uri' => 'img',
    	// 		],
    	// 	],
     //    ]);
        $this->img('test-image.jpg', ['w' => 400, 'ch' => 30])->shouldBe('<img width="400" height="30" src="//assets.example.com/img/test-image___w400-ch30.jpg">');
        
        if( ! is_file($f))
        {
        	throw new FailureException("Generation of image failed");
        }
    }

    function it_can_create_a_png_html_element_with_height()
    {
        $f = __DIR__."/../../test-images/img/sub-directory/test.image___h300.png";
        if(is_file($f))
        {
            unlink($f);
        }
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'root-path' => __DIR__.'/../../test-images',
        //     'images-directories' => [
        //         'image' => [
        //             'source' => 'images',
        //             'uri' => 'img',
        //         ],
        //     ],
        // ]);
        $this->img('sub-directory/test.image.png', ['h' => 300])->shouldBe('<img height="300" src="//assets.example.com/img/sub-directory/test.image___h300.png">');
        
        if( ! is_file($f))
        {
            throw new FailureException("Generation of image failed");
        }
    }

    function it_can_create_a_img_html_element_with_height_and_crop_width()
    {
        $i1 = 210;
        $i2 = 390;

        $f = __DIR__."/../../test-images/img/test-image___h$i2-cw$i1.jpg";
        if(is_file($f))
        {
            unlink($f);
        }
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'root-path' => __DIR__.'/../../test-images',
        //     'images-directories' => [
        //         'image' => [
        //             'source' => 'images',
        //             'uri' => 'img',
        //         ],
        //     ],
        // ]);
        $this->img('test-image.jpg', ['cw' => $i1, 'h' => $i2])->shouldBe('<img width="'.$i1.'" height="'.$i2.'" src="//assets.example.com/img/test-image___h'.$i2.'-cw'.$i1.'.jpg">');
        
        if( ! is_file($f))
        {
            throw new FailureException("Generation of image failed");
        }
    }

    function it_can_create_a_img_html_element_with_width_and_crop_width_and_crop_height()
    {
        $f = __DIR__."/../../test-images/img/test-image___w40-cw20-ch20.jpg";
        if(is_file($f))
        {
            unlink($f);
        }
    	// $this->beConstructedWith([
    	// 	'url' => 'assets.example.com',
    	// 	'root-path' => __DIR__.'/../../test-images',
    	// 	'images-directories' => [
    	// 		'image' => [
    	// 			'source' => 'images',
    	// 			'uri' => 'img',
    	// 		],
    	// 	],
     //    ]);
        $this->img('test-image.jpg', ['w' => 40, 'cw' => 20, 'ch' => 20])->shouldBe('<img width="20" height="20" src="//assets.example.com/img/test-image___w40-cw20-ch20.jpg">');
        
        if( ! is_file($f))
        {
        	throw new FailureException("Generation of image failed");
        }
    }

    function it_can_create_a_img_html_element_with_size()
    {
        $f = __DIR__."/../../test-images/img/test-image___w50-h50.jpg";
        if(is_file($f))
        {
            unlink($f);
        }
   //  	$this->beConstructedWith([
   //  		'url' => 'assets.example.com',
   //  		'root-path' => __DIR__.'/../../test-images',
   //  		'images-directories' => [
   //  			'image' => [
   //  				'source' => 'images',
   //  				'uri' => 'img',
   //  			],
   //  		],
   //  		'images-sizes' => [
			// 	'tiny' => [50, 50],
			// 	'small' => [200, NULL],
			// 	'medium' => [500, NULL],
			// 	'large' => [1000, NULL],
			// 	'xlarge' => [1500, NULL],
			// 	'xxlarge' => [2000, NULL],
			// ],
   //      ]);
        $this->img('test-image.jpg', ['size' => 'tiny'])->shouldBe('<img width="50" height="50" src="//assets.example.com/img/test-image___w50-h50.jpg">');
        
        if( ! is_file($f))
        {
        	throw new FailureException("Generation of image failed");
        }
    }

    function it_can_create_a_img_html_element_with_size_landscape()
    {
        $f = __DIR__."/../../test-images/img/test-image___w500-h200.jpg";
        if(is_file($f))
        {
            unlink($f);
        }
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'root-path' => __DIR__.'/../../test-images',
        //     'images-directories' => [
        //         'image' => [
        //             'source' => 'images',
        //             'uri' => 'img',
        //         ],
        //     ],
        //     'images-sizes' => [
        //         'tiny' => [50, 50],
        //         'small' => [200, NULL],
        //         'medium-landscape' => [500, 200],
        //         'large' => [1000, NULL],
        //         'xlarge' => [1500, NULL],
        //         'xxlarge' => [2000, NULL],
        //     ],
        // ]);
        $this->img('test-image.jpg', ['size' => 'medium-landscape'])->shouldBe('<img width="500" height="200" src="//assets.example.com/img/test-image___w500-h200.jpg">');
        
        if( ! is_file($f))
        {
            throw new FailureException("Generation of image failed");
        }
    }

    function it_can_create_a_img_html_element_with_size_portrait()
    {
        $f = __DIR__."/../../test-images/img/test-image___w150-h420.jpg";
        if(is_file($f))
        {
            unlink($f);
        }
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'root-path' => __DIR__.'/../../test-images',
        //     'images-directories' => [
        //         'image' => [
        //             'source' => 'images',
        //             'uri' => 'img',
        //         ],
        //     ],
        //     'images-sizes' => [
        //         'tiny' => [50, 50],
        //         'small' => [200, NULL],
        //         'medium-portrait' => [150, 420],
        //         'large' => [1000, NULL],
        //         'xlarge' => [1500, NULL],
        //         'xxlarge' => [2000, NULL],
        //     ],
        // ]);
        $this->img('test-image.jpg', ['size' => 'medium-portrait'])->shouldBe('<img width="150" height="420" src="//assets.example.com/img/test-image___w150-h420.jpg">');
        
        if( ! is_file($f))
        {
            throw new FailureException("Generation of image failed");
        }
    }

    function it_can_create_a_img_html_element_with_width_percent_value()
    {
        $f = __DIR__."/../../test-images/img/test-image___w1024.jpg";
        if(is_file($f))
        {
            unlink($f);
        }
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'root-path' => __DIR__.'/../../test-images',
        //     'images-directories' => [
        //         'image' => [
        //             'source' => 'images',
        //             'uri' => 'img',
        //         ],
        //     ],
        //     'images-sizes' => [
        //         'tiny' => [50, 50],
        //         'small' => [200, NULL],
        //         'medium-portrait' => [150, 420],
        //         'large' => [1000, NULL],
        //         'xlarge' => [1500, NULL],
        //         'xxlarge' => [2000, NULL],
        //         '100%' => [1024, NULL],
        //     ],
        // ]);
        $this->img('test-image.jpg', ['w' => '100%'])->shouldBe('<img width="100%" src="//assets.example.com/img/test-image___w1024.jpg">');
        
        if( ! is_file($f))
        {
            throw new FailureException("Generation of image failed");
        }
    }

    function it_can_create_a_img_html_element_with_image_size_recursive()
    {
        $f = __DIR__."/../../test-images/img/test-image___w1000.jpg";
        if(is_file($f))
        {
            unlink($f);
        }
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'root-path' => __DIR__.'/../../test-images',
        //     'images-directories' => [
        //         'image' => [
        //             'source' => 'images',
        //             'uri' => 'img',
        //         ],
        //     ],
        //     'images-sizes' => [
        //         'tiny' => [50, 50],
        //         'small' => [200, NULL],
        //         'medium-portrait' => [150, 420],
        //         'large' => [1000, NULL],
        //         'xlarge' => [1500, NULL],
        //         'xxlarge' => [2000, NULL],
        //         '100%' => ['large', NULL],
        //     ],
        // ]);
        $this->img('test-image.jpg', ['w' => '100%'])->shouldBe('<img width="100%" src="//assets.example.com/img/test-image___w1000.jpg">');
        
        if( ! is_file($f))
        {
            throw new FailureException("Generation of image failed");
        }
    }

    function it_can_create_a_img_html_element_with_width_image_size()
    {
        $f = __DIR__."/../../test-images/img/test-image___w1500.jpg";
        if(is_file($f))
        {
            unlink($f);
        }
        // $this->beConstructedWith([
        //     'url' => 'assets.example.com',
        //     'root-path' => __DIR__.'/../../test-images',
        //     'images-directories' => [
        //         'image' => [
        //             'source' => 'images',
        //             'uri' => 'img',
        //         ],
        //     ],
        //     'images-sizes' => [
        //         'tiny' => [50, 50],
        //         'small' => [200, NULL],
        //         'medium-portrait' => [150, 420],
        //         'large' => [1000, NULL],
        //         'xlarge' => [1500, NULL],
        //         'xxlarge' => [2000, NULL],
        //         '100%' => ['large', NULL],
        //     ],
        // ]);
        $this->img('test-image.jpg', ['w' => 'xlarge'])->shouldBe('<img width="1500" src="//assets.example.com/img/test-image___w1500.jpg">');
        
        if( ! is_file($f))
        {
            throw new FailureException("Generation of image failed");
        }
    }
}
