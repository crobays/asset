<?php namespace Crobays\Asset\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImageGeneratorCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'asset:generate-css-images';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate image with custom sizes.';

	/**
	 * @var AssetGenerator
	 */
	protected $generate;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(\Crobays\Asset\Image\ImageGenerator $generate)
	{
		parent::__construct();
		$this->generate = $generate;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		 //$this->generate->
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('css-file', InputArgument::REQUIRED, 'Fetch images urls from a CSS file.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_REQUIRED, 'Fetch images urls from CSS file.', null),
		);
	}

}
