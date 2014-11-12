<?php namespace Crobays\Asset\Image;

use Intervention\Image\ImageManager;
use Crobays\Asset\Exception;

class ImageGenerator implements \Crobays\Asset\Interfaces\ImageUrlParseReceiver {

	protected $image;

	protected $dest_path;

	protected $source_path;

    protected $extension;

	protected $multiplier = 1;

	protected $width = NULL;

	protected $height = NULL;

	protected $crop_width = NULL;

	protected $crop_height = NULL;

	protected $crop_x = NULL;

	protected $crop_y = NULL;

	public function make()
    {
        if ( ! $this->outdated())
        {
            return $this;
        }

        if ( ! is_file($this->source_path))
        {
            throw new Exception\InvalidImagePathException("Invalid image: ".$this->source_path, 1);
        }
        
        @mkdir(dirname($this->dest_path), 0777, TRUE);
        if ( ! $this->canBeManipulated() || ! $this->hasManipulations())
        {
            copy($this->source_path, $this->dest_path);
            return $this;
        }

        return $this->generate();
    }

    public function generate()
    {
        $image_manager = new ImageManager;
        $this->image = $image_manager->make($this->source_path);
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
        
        $this->image->save($this->dest_path);
        return $this;
    }

    /**
     * Get image response
     *
     * @return string
     */
    public function response()
    {
        if ( ! $this->image)
        {
            $this->generate();
        }
        return $this->image->response($this->extension);
    }

    /**
     * Whether the image type can be manipulated
     *
     * @return boolean
     */
    protected function canBeManipulated()
    {
        return in_array($this->extension, ['jpg', 'jpeg', 'png', 'gif']);
    }

    /**
     * Whether there are any manipulation paramenters set
     *
     * @return boolean
     */
    protected function hasManipulations()
    {
        return $this->width() || $this->height() || $this->cropWidth() || $this->cropHeight() || $this->cropX() || $this->cropY();
    }

    protected function outdated()
    {
        if ( ! is_file($this->dest_path))
        {
            return TRUE;
        }
        return filemtime($this->dest_path) < filemtime($this->source_path);
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

    public function setSourcePath($source_path)
    {
        $this->extension = strtolower(trim(strrchr($source_path, '.'), '.'));
        $this->source_path = $source_path;
    }

    public function setDestPath($dest_path)
    {
        $this->dest_path = $dest_path;
    }

    public function setWidth($width)
    {
        $this->width = $this->fetchValue($width);
    }

    public function setHeight($height)
    {
        $this->height = $this->fetchValue($height);
    }

    public function setCropWidth($crop_width)
    {
        $this->crop_width = $this->fetchValue($crop_width);
    }

    public function setCropHeight($crop_height)
    {
        $this->crop_height = $this->fetchValue($crop_height);
    }

    public function setCropX($crop_x)
    {
        $this->crop_x = $this->fetchValue($crop_x);
    }

    public function setCropY($crop_y)
    {
        $this->crop_y = $this->fetchValue($crop_y);
    }

    public function setMultiplier($multiplier)
    {
        $this->multiplier = $this->fetchValue($multiplier);
    }

    public function fetchValue($value)
    {
        return is_null($value) ? NULL : intval($value);
    }
}