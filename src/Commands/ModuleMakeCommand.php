<?php
namespace Akk7300\ModuleGenerator\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;


class ModuleMakeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'make:module {class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module folder';

    protected $file;

    /**
    * Create a new command instance.
    *
    * @return void
    */
   public function __construct(Filesystem $files)
   {
        parent::__construct();

        $this->files = $files;
   }

   /**
    * Execute the console command.
    */
    public function handle(){

        $className = $this->getSingularClassName($this->argument('class'));

        $ApiFolder = $this->checkFile('modules/Api');
        $FoundationFolder = $this->checkFile('modules/Foundations/Domain');

        if($ApiFolder || $FoundationFolder){
           return $this->error($this->argument('class').' Module already exists');
        }

        $paths = $this->getSourceFilePath($className);

        foreach($paths as $index => $path){

            $this->makeDirectory(dirname($path));
            $contents = $this->getSourceFile($index);

            if(!$this->files->exists($path)){
                $this->files->put($path, $contents);
                $this->info("File : {$path} created");
            }
        }

    }

    /**
     * Return the stub file path
     * @return string
     *
    */
    public function getStubPath()
    {
        return [
                __DIR__.'/stubs/controller.stub',
                __DIR__.'/stubs/service.stub',
                __DIR__.'/stubs/validator.stub',
                __DIR__.'/stubs/provider.stub',
                __DIR__.'/stubs/repository.stub',
                __DIR__.'/stubs/interface.stub',
                __DIR__.'/stubs/model.stub',

        ];
    }

    public function getStubVariables()
    {   
        return [
            'namespace' => config('modulegenerator.namespace'),
            'class' => $this->getSingularClassName($this->argument('class')),
            'classes' => $this->getPluralClassName($this->argument('class')),
        ];
    }

    public function getSourceFile($index)
    {
        return $this->getStubContents($this->getStubPath()[$index], $this->getStubVariables());
    }

    public function getStubContents($stub , $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace)
        {
            $contents = str_replace('{{ '.$search.' }}' , $replace, $contents);
        }

        return $contents;
    }

    /**
     * Get the full path of generate class
     *
     * @return array
    */
    public function getSourceFilePath($className)
    {
        $apiPrefix = base_path().'/modules/Api/'.$this->getPluralClassName($className);
        $foundationPrefix = base_path().'/modules/Foundations/Domain/'.$this->getPluralClassName($className);

        return $paths = [
            $apiPrefix . '/Controllers/' . $className . 'Controller.php',
            $apiPrefix . '/Services/' . $className . 'Service.php',
            $apiPrefix . '/Validation/' . $className . 'Validator.php',
            $foundationPrefix . '/Providers/Bind' . $className . 'ServiceProvider.php',
            $foundationPrefix . '/Repositories/Eloquent/' . $className . 'Repository.php',
            $foundationPrefix . '/Repositories/' . $className . 'RepositoryInterface.php',
            $foundationPrefix . '/' . $className . '.php',
        ];
    }

    /**
     * Return the Singular Capitalize Name
     * @param $name
     * @return string
    */
    public function getSingularClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }

    /**
     * Return the Plural Capitalize Name
     * @param $name
     * @return string
    */
    public function getPluralClassName($name)
    {
        return ucwords(Pluralizer::plural($name));
    }


    /**
     * Check file exist
     * @return boolean
     *
    */
    protected function checkFile($path)
    {
        return file_exists(base_path(). "/" . $path . "/" . $this->getPluralClassName($this->argument('class')));
    }


    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
    */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

}
