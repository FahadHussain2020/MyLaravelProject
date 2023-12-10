<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class CreateModelWithRepoServiceStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:all {modelName?}{migration?}{apiVersion?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentApiVersion = Config::get('app.api_latest');
        $modelName = $this->argument('modelName');
        $apiVersion = $this->argument('apiVersion');
        $migration = $this->argument('migration');

        if (!$modelName) {
            dd('Model name required', 'Example => php artisan create:model modelName migrationflag apiVerion');
        }
        if ($migration == null) {
            dd('Migration flag required', 'Example => php artisan create:model modelName migrationflag apiVerion');
        }
        if (!$apiVersion) {
            dd('Api version required', 'Example => php artisan create:model modelName migrationflag apiVerion');
        }
        if ($currentApiVersion != $apiVersion) {
            dd('Api Version not matched with project current API version');
        }


        try {


            // create Model
            if ($migration) {
                $this->call('make:model', [
                    'name' => $modelName,
                    '-m' => true,
                ]);
            } else {
                $this->call('make:model', [
                    'name' => $modelName,
                ]);
            }


            // create Resource
            $this->call('make:resource', [
                'name' => $modelName . 'Resource',
            ]);






            // // create repository interface
            $repoInterfaceName = $modelName . 'RepositoryInterface';
            $data = $this->getRepoInterFaceFileContent($repoInterfaceName, $apiVersion);
            $repoInterface = $repoInterfaceName . '.php';

            $destinationPath = app_path() . '/Interfaces/Repositories/V' . $apiVersion . '/';
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            File::put($destinationPath . $repoInterface, $data);

            // create service class interface
            $serviceInterfaceName = $modelName . 'ServiceClassInterface';
            $data = $this->getServiceInterFaceFileContent($serviceInterfaceName, $apiVersion);
            $serviceInterface = $serviceInterfaceName . '.php';

            $destinationPath = app_path() . '/Interfaces/Services/V' . $apiVersion . '/';
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            File::put($destinationPath . $serviceInterface, $data);


            // create Controller
            $controllerName = $modelName . 'Controller';
            $data = $this->controllerFileContent($serviceInterfaceName, $controllerName, $apiVersion);
            $controller = $controllerName . '.php';

            $destinationPath = app_path() . '/Http/Controllers/API/V' . $apiVersion . '/';
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            File::put($destinationPath . $controller, $data);

            // Repository Class
            $repoClassName = $modelName . 'Repository';
            $data = $this->getRepoClassFileContent($repoInterfaceName, $repoClassName, $apiVersion, $modelName);
            $repoClass = $repoClassName . '.php';

            $destinationPath = app_path() . '/Repository/V' . $apiVersion . '/';
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            File::put($destinationPath . $repoClass, $data);

            // // Create Service Class
            $serviceClass = $modelName . 'ServiceClass';
            $data = $this->getServiceClassFileContent($repoClassName, $serviceInterfaceName, $serviceClass, $apiVersion);
            $serviceInterface = $serviceClass . '.php';

            $destinationPath = app_path() . '/Services/V' . $apiVersion . '/';
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            File::put($destinationPath . $serviceInterface, $data);


            dd('Model , Migration , ServiceInterface , Service Class , Repository Interface , Repository Class | Created Successfully');
        } catch (Exception $e) {
            dd($e->getMessage());
        }



        return 0;
    }

    function getRepoInterFaceFileContent($fileName, $apiVersion)
    {
        $content = "<?php
namespace App\Interfaces\Repositories\V" . $apiVersion . ";


interface " . $fileName . "
{

}";

        return $content;
    }


    function controllerFileContent($serviceInterfaceName, $fileName, $apiVersion)
    {
        $content = "<?php

namespace App\Http\Controllers\API\V" . $apiVersion . ";

use App\Http\Controllers\Controller;
use App\Interfaces\Services\V" . $apiVersion . "\\" . $serviceInterfaceName . ";


class " . $fileName . " extends Controller
{

    private " . $serviceInterfaceName . " " . '$interface' . ";

    public function __construct( " . $serviceInterfaceName . " " . '$interface' . ")
    {
        " . '$this->interface = $interface;' . "
    }

}";



        return $content;
    }

    function getServiceInterFaceFileContent($fileName, $apiVersion)
    {
        $content = "<?php

namespace App\Interfaces\Services\V" . $apiVersion . ";


interface " . $fileName . "
{

}";

        return $content;
    }

    function getServiceClassFileContent($repoClassName, $serviceInterfaceName, $fileName, $apiVersion)
    {
        $content = "<?php

namespace App\Services\V" . $apiVersion . ";

use App\Interfaces\Services\V" . $apiVersion . "\\" . $serviceInterfaceName . ";
use App\Repository\V" . $apiVersion . "\\" . $repoClassName . ";

class " . $fileName . " implements " . $serviceInterfaceName . "
{
    private " . $repoClassName . " " . '$repository' . ";

    public function __construct(" . $repoClassName . " " . '$repo' . ")
    {
        " . '$this->repository = $repo;' . "
    }

}";
        return $content;
    }

    function getRepoClassFileContent($repoInterfaceName, $fileName, $apiVersion, $modelName)
    {
        $content = "<?php

        namespace App\Repository\V" . $apiVersion . ";

        use App\Interfaces\Repositories\V" . $apiVersion . "\\" . $repoInterfaceName . ";

        use App\Models\\" . $modelName . ";

        class " . $fileName . " implements " . $repoInterfaceName . "
        {
            private " . '$model' . ";

            public function __construct(" . $modelName . " " . '$modelNameObject' . ")
            {
                " . '$this->model = $modelNameObject;' . "
            }

        }";




        return $content;
    }
}
