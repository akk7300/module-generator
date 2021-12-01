<?php
namespace Akk7300\ModuleGenerator\Commands;

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

    public function handle(){
        if($this->getSingularClassName($this->argument('class')) == $this->argument('class'))
        {
           return $this->error('Please write a class name in plural');
        }

        $className = $this->getSingularClassName($this->argument('class'));

        $ApiFolderExists = file_exists(base_path().'/modules/Api/'.$this->argument('class'));

        $FoundationFolderExists = file_exists(base_path().'/modules/Foundations/Domain/'.$this->argument('class'));

        if($ApiFolderExists || $FoundationFolderExists){
           return $this->error($this->argument('class').' Module already exists');
        }

        $controllerPath = base_path().'/modules/Api/'.$this->getPluralClassName($className).'/Controllers';
        $servicePath = base_path().'/modules/Api/'.$this->getPluralClassName($className).'/Services';
        $validatorPath = base_path().'/modules/Api/'.$this->getPluralClassName($className).'/Validation';

        $providerPath = base_path().'/modules/Foundations/Domain/'.$this->getPluralClassName($className).'/Providers';
        $repositoryPath = base_path().'/modules/Foundations/Domain/'.$this->getPluralClassName($className).'/Repositories/Eloquent';

        File::makeDirectory($controllerPath, 0755, true);
        File::makeDirectory($servicePath, 0755, true);
        File::makeDirectory($validatorPath, 0755, true);

        File::makeDirectory($providerPath, 0755, true);
        File::makeDirectory($repositoryPath, 0755, true);

        $paths = $this->getSourceFilePath($className);

        foreach($paths as $index => $path){
            
            $contents = $this->getSourceFile($index);

            if(!$this->files->exists($path)){
                $this->files->put($path, $contents);
                $this->info("File : {$path} created");
            }
        }

    }

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

    public function getSourceFilePath($className)
    {
        return $paths = [
            base_path().'/modules/Api/'.$this->getPluralClassName($className).'/Controllers/'.$className.'Controller.php',
            base_path().'/modules/Api/'.$this->getPluralClassName($className).'/Services/'.$className.'Service.php',
            base_path().'/modules/Api/'.$this->getPluralClassName($className).'/Validation/'.$className.'Validator.php',
            base_path().'/modules/Foundations/Domain/'.$this->getPluralClassName($className).'/Providers/Bind'.$className.'ServiceProvider.php',
            base_path().'/modules/Foundations/Domain/'.$this->getPluralClassName($className).'/Repositories/Eloquent/'.$className.'Repository.php',
            base_path().'/modules/Foundations/Domain/'.$this->getPluralClassName($className).'/Repositories/'.$className.'RepositoryInterface.php',
            base_path().'/modules/Foundations/Domain/'.$this->getPluralClassName($className).'/'.$className.'.php',
        ];
    }

    public function getSingularClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }

    public function getPluralClassName($name)
    {
        return ucwords(Pluralizer::plural($name));
    }

}
