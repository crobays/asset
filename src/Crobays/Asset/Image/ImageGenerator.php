<?php namespace Crobays\Asset\Image;

use Intervention\Image\ImageManager as Manipulator;
use Crobays\Asset\Exception;

class ImageGenerator {

	protected $manipulator;

	protected $image;

	protected $new_path;

	protected $source_path;

	protected $multiplier = 1;

	protected $width = NULL;

	protected $height = NULL;

	protected $crop_width = NULL;

	protected $crop_height = NULL;

	protected $crop_x = NULL;

	protected $crop_y = NULL;

	public function __construct()
    {
        $this->manipulator = new Manipulator;
    }

	public function make()
    {
        if ( ! $this->outdated())
        {
            return $this;
        }

        @mkdir(dirname($this->new_path), 0777, TRUE);

        if ( ! $this->canBeManipulated())
        {
            copy($this->source_path, $this->new_path);
            return $this;
        }

        return $this->generate();
    }

    public function generate()
    {
        $this->image = $this->manipulator->make($this->source_path);

        if($this->width() && $this->height())
        {
            $this->image->fit($this->width(), $this->height(), function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        else if($this->width() || $this->height())
        {
            $this->image->resize($this->width(), $this->height(), function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        if($this->cropWidth() || $this->cropHeight())
        {
            $cw = $this->cropWidth() ?: $this->width() ?: $this->image->width();
            $ch = $this->cropHeight() ?: $this->height() ?: $this->image->height();
            $this->image->crop($cw, $ch, $this->cropX(), $this->cropY());
        }
        
        $this->image->save($this->new_path);
        return $this;
    }

    /**
     * Get image response
     *
     * @return string
     */
    public function response()
    {
        return $this->image->response($this->extension());
    }

    /**
     * Whether the image type can be manipulated
     *
     * @return boolean
     */
    public function canBeManipulated()
    {
        return in_array($this->extension(), ['jpg', 'jpeg', 'png', 'gif']);
    }

    /**
     * Get the extension of the file
     *
     * @return bool
     */
    public function extension()
    {
    	return strtolower(trim(strrchr($this->source_path, '.'), '.'));
    }

    public function outdated()
    {
        if ( ! is_file($this->new_path))
        {
            return TRUE;
        }
        return filemtime($this->new_path) < filemtime($this->source_path);
    }

    public function multiplier()
    {
        return $this->multiplier;
    }

    public function multiply($val)
    {
    	if (is_null($val))
    	{
    		return NULL;
    	}
        return $val * $this->multiplier();
    }

    protected function width()
    {
        return $this->multiply($this->width);
    }

    protected function height()
    {
        return $this->multiply($this->height);
    }

    protected function cropWidth()
    {
        return $this->multiply($this->crop_width);
    }

    protected function cropHeight()
    {
        return $this->multiply($this->crop_height);
    }

    protected function cropX()
    {
        return $this->multiply($this->crop_x);
    }

    protected function cropY()
    {
        return $this->multiply($this->crop_y);
    }

    public function setSourcePath($source_path)
    {
    	if ( ! is_file($source_path))
    	{
    		throw new Exception\InvalidImagePathException("Invalid source image: $source_path", 1);
    	}
        $this->source_path = $source_path;
    }

    public function setNewPath($new_path)
    {
        $this->new_path = $new_path;
    }

    public function setMultiplier($multiplier)
    {
        $this->multiplier = $multiplier;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function setCropWidth($crop_width)
    {
        $this->crop_width = $crop_width;
    }

    public function setCropHeight($crop_height)
    {
        $this->crop_height = $crop_height;
    }

    public function setCropX($x)
    {
        $this->crop_x = $x;
    }

    public function setCropY($y)
    {
        $this->crop_y = $y;
    }
}