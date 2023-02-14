<?php
namespace Hasatbey\Tugiclient\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * Publish the assets to the public directory
 *
 */
class PublishCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tugiclient:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the tugiclient assets';

 
    protected $files;

    protected $publishPath;

    /**
     * Create a new Publish command
     *
     * @param string $publishPath
     */
    public function __construct($files, $publishPath)
    {
        parent::__construct();

        $this->files = $files;
        $this->publishPath = $publishPath;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        $package = 'hasatbey/tugiclient';
        $destination = $this->publishPath . "/packages/{$package}";

        if ( ! is_null($path = $this->getElfinderPath())) {
            if ($this->files->exists($destination)) {
                $this->files->deleteDirectory($destination);
                $this->info('Old published Assets have been removed');
            }
            $copyElfinder = $this->copyElfinderFiles($destination);
        } else {
            $copyElfinder = false;
            $this->error('Could not find elfinder path');
        }

        if ( ! is_null($path = $this->getPath())) {
            $copyPublic = $this->files->copyDirectory($path, $destination);
        } else {
            $copyPublic = false;
            $this->error('Could not find public path');
        }

        if ($copyElfinder && $copyPublic) {
            $this->info('Published assets to: '.$package);
        } else {
            $this->error('Could not publish alles assets for '.$package);
        }

    }

    /**
     * Copy specific directories from elFinder to their destination
     *
     * @param $destination
     * @return bool
     */
    protected function copyElfinderFiles($destination)
    {
        $result = true;
        $directories = array('js', 'css', 'img', 'sounds');
        $elfinderPath = $this->getElfinderPath();
        foreach($directories as $dir){
            $path = $elfinderPath.'/'.$dir;
            $success = $this->files->copyDirectory($path, $destination.'/'.$dir);
            $result = $success && $result;
        }
        return $result;
    }

    /**
     *  Get the path of the public folder, to merge with the elFinder folders.
     */
    protected function getPath(){
        return __DIR__ .'/../../resources/assets';
    }

    /**
     * Find the elFinder path from the vendor dir.
     *
     * @return string
     */
    protected function getElfinderPath()
    {
        $reflector = new \ReflectionClass('Hasatbey/Tugiclient');
        return realpath(dirname($reflector->getFileName()) . '/..');
    }

}
