<?php
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

function getModelClassNames()
{
    $modelsPath = app_path('Models');
    $modelFiles = File::allFiles($modelsPath);

    $modelClasses = [];

    foreach ($modelFiles as $modelFile) {
        $relativePath = $modelFile->getRelativePathname();
        $class_name = Str::replaceLast('.php', '', Str::replace('/', '\\', $relativePath));
        $class = 'App\\Models\\' . $class_name;

        if (class_exists($class)) {
            $modelClasses[] = $class_name;
        }
    }

    return $modelClasses;
}

function getFunctionName($type='',$string='')
{
    //camel case
    return lcfirst(str_replace(' ', '', ucwords( $type.' '.$string)));
}

function getClassName($string='')
{
    //StudlyCase (PascalCase)
    return str_replace(' ', '', ucwords($string));
}
