# Crobays / Asset

Simple asset helper to get asset (image, picture, stylesheet, script) elements or urls. Images and pictures have an option to resized and/or cropped dynamically.

#### Require

	"crobays/asset": "dev-master"

### Usage

`php artisan config:publish crobays/asset` creates a config file in your Lavavel project: `app/packages/crobays/asset/config.php`

#### Images

	Asset::img('my-image.png', ['w' => 400])
	
grabs `images/my-image.png` to fit width 400px, saves it to `img/my-image___w400.png` (also create a @2x version for hi-dpi displays, you need to include retina.js to use this functionality) and returns

	<img width="400" src="//assets.example.com/img/my-image___w400.png" 
		data-src2x="//assets.example.com/img/my-image___w400-@2x.png">

---

Resize and crop the image by providing both width and height

	Asset::img('my-image.png', ['w' => 250, 'h' => 250])
	
grabs `images/my-image.png` to fit width 250px and height 250px, saves it to `img/my-image___w250-h250.png` and returns
	
	<img width="250" height="250" src="//assets.example.com/img/my-image___w250-h250.png" 
		data-src2x="//assets.example.com/img/my-image___w250-h250-@2x.png">

---

Provide a size (sizes defined in `app/packages/crobays/asset/config.php`)

	Asset::img('my-image.png', ['s' => 'xlarge'])
	
makes image and returns
	
	<img width="960" src="//assets.example.com/img/my-image___w960.png" 
		data-src2x="//assets.example.com/img/my-image___w960-@2x.png">

---

Provide a special 100% size (defined in the `app/packages/crobays/asset/config.php`)

	Asset::img('my-image.png', ['s' => '100%'])

makes image and returns
	
	<img width="100%" src="//assets.example.com/img/my-image___w800.png" 
		data-src2x="//assets.example.com/img/my-image___w800-@2x.png">

---

It allows you to add custom HTML attributes

	Asset::img('my-image.png', ['w' => 300, 'class' => 'image'])
	
makes image and returns
	
	<img width="250" class="image" src="//assets.example.com/img/my-image___w300.png" 
		data-src2x="//assets.example.com/img/my-image___w300-@2x.png">

---

#### Pictures

	Asset::pic('picture.jpg', ['w' => 1440])

grabs `pictures/picture.jpg` to fit width 1440px, saves it to `pictures/picture___w1440.jpg` (also create a @2x version for hi-dpi displays, you need to include retina.js to use this functionality) and returns

	<img width="1440" src="//assets.example.com/pic/picture___w1440.jpg" 
		data-src2x="//assets.example.com/pic/picture___w1440-@2x.jpg">

(Inherits also all image functionalty found above)

---

#### CSS

(default stylesheet defined in the `app/packages/crobays/asset/config.php`)

	Asset::css()
	Asset::css('another-style.css')

returns
	
	<link type="text/css" href="//assets.example.com/style.css" rel="stylesheet">
	<link type="text/css" href="//assets.example.com/another-style.css" rel="stylesheet">

---

#### Javascript

(default script defined in the `app/packages/crobays/asset/config.php`)

	Asset::js()
	Asset::js('another-script.js')
	
returns
	
	<script type="text/javascript" src="//assets.example.com/script.js"></script>
	<script type="text/javascript" src="//assets.example.com/another-script.js"></script>





### Todo
- phpspec tests
- codeception tests
- comment block documentation
