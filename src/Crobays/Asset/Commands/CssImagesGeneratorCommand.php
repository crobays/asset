<?php namespace Crobays\Asset\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Crobays\Asset\Image\ImagesGeneratorManager;
use Crobays\Asset\Image\CssImageFinder;

class CssImagesGeneratorCommand extends Command implements \Crobays\Asset\Interfaces\LoggerInterface {

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
	protected $generator;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->generator = new ImagesGeneratorManager(new CssImageFinder, $this);
		$this->generator->setRootPath(\Config::get('asset::root-path'));
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$css_file = $this->argument('css-file');
		$this->generator->generateImagesFromFile($css_file);
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
