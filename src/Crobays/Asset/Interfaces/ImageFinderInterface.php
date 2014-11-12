<?php namespace Crobays\Asset\Interfaces;

interface ImageFinderInterface {

	public function searchImages();
	public function setFile($file);
	public function getFileContents();

}