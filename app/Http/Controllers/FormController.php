<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forms = Form::all();
        return view('k-frame.forms.index',compact('forms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('k-frame.forms.form',['type'=>'create','editable'=>true]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:forms',
            'description' => 'nullable|string',
            'dependencies' => 'nullable',

        ]);

        $form = Form::create($validatedData);
        return redirect()->route('forms.edit',[$form->id])->with('success', 'Record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
        return view('k-frame.forms.form',['type'=>'view','editable'=>false,'form'=>$form->load(['fields'])]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form)
    {
        //$form = Form::find($id);
        return view('k-frame.forms.form',['type'=>'edit','editable'=>true,'form'=>$form->load(['fields'])]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Form $form)
    {
        $validatedData = $request->validate([
            'name' => ['required','string','max:255',Rule::unique('forms')->ignore($form->id)],
            'description' => 'nullable|string',
            'dependencies' => 'nullable',
            'identifier' => 'nullable',
        ]);

        $form->update($validatedData);
        return redirect()->route('forms.show',[$form->id])->with('success', 'Record created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        $form->delete();

        return redirect()->route('forms.index');
    }

    public function generate(Form $form)
    {
        // Define the name of the resources
        $modelName = getClassName($form->name);
        $controllerName = $modelName.'Controller';
        $tableName = getTableName($modelName);
        //$model_parm = strtolower($modelName);
        $model_parm = getParmByTableName($tableName);
        $name = $tableName;
        $viewPath = resource_path("views/{$name}");
        // 1. Generate the Model, Controller, and Migration
        Artisan::call('make:model', ['name' => $modelName, '--migration' => true, '--controller' => true, '--resource' => true]);

        // 2. Add custom code to the generated files
        $migrationPath = database_path("migrations/" . date('Y_m_d_His') . "_create_{$tableName}_table.php");
        $modelPath = app_path("Models/{$modelName}.php");
        $controllerPath = app_path("Http/Controllers/{$controllerName}.php");
        $controllerNamespace = '\App\Http\Controllers';
        $modelNamespace = '\App\Models';

        // Instantiate the Filesystem class
        $filesystem = new Filesystem();

        $booleanFields=$form->fields()->where('type', 'BOOLEAN')->pluck('field')->map(function($field_name) {
            return "'{$field_name}'";
        })->toArray();

        $selectionFields=$form->fields->filter(fn($field) => $field->type === 'SELECTION');

        $belongsToFields=$form->fields->filter(fn($field) => $field->type === 'BELONGSTO');

        // Check if the model file exists
        if ($filesystem->exists($modelPath)) {
            // Remove all existing content
            $filesystem->put($modelPath, "<?php\n\n");
            $fillable = $form->fields->pluck('field')
                        ->map(function($field) {
                            return "'{$field}'";
                        })
                        ->implode(',');

            $cast = '';
            foreach ($booleanFields as $field) {
                $cast .=  $field ." => 'boolean', ";
            }
            
            


            $static_arrays = "//static arrays\n";
            $static_functions = '';
            $appends = '';
            $functions = "//functions\n";
            $relations = "//relations\n";

            $custom_attributes = '//custom attributes';
            if (!$form->fields()->where('field', 'name')->exists()) {
                $result = '';
                if ($arrayOfStrings = explode(',', $form->identifier)) {
                    foreach ($arrayOfStrings as $string) {
                       
                        if ($string === 'id') {
                            $result .= "\$this->$string.";
                        }
                        else if ($form->fields->contains(fn($field) => $field->field === $string)) {
                            if( $relationField = $belongsToFields->filter(fn($field) => $field->field == $string)->first()){
                                $relation_field_name = getFunctionNameByClass($relationField->related_model).'->name';
                                $result .= "\$this->$relation_field_name ?? \$this->$string ?? ''";
                            }
                            else{
                                $result .= "\$this->$string.";
                            }
                        } else {
                            $result .= "'$string'.";
                        }
                    }
                }
                $result = rtrim($result, '. ');
                $appends .= "'name',";
                $custom_attributes .= "
    public function getNameAttribute()
    {
        return {$result} ;
    }";
            }

            //$relationMap = '';
            foreach ($belongsToFields as $field) {
                //$relationMap .= "'.$field->field.' => '.$field->related_model.', \n";
                $relations .= "
    public function ".getFunctionNameByClass($field->related_model)."()
    {
        return \$this->belongsTo($field->related_model::class, '$field->field');
    }";
            }

            // Optionally, remove the trailing comma and space
            $cast = rtrim($cast, ', ');
            $appends = rtrim($appends, ', '); 
            // $relationMap = rtrim($relationMap, ', ');

            // if($relationMap){
            //     $static_arrays .=  "private static relationMap =  [".$relationMap."]; \n \n";
            // }

            foreach ($selectionFields as $field) {
                $formattedPairs = array_map(
                    fn($key, $value) => "'$key' => '$value'",
                    array_keys($field->selections),
                    $field->selections
                );
                
                // Convert the array to a comma-separated string
                $formattedString = implode(', ', $formattedPairs);
                $static_arrays .=  "
    private static \${$field->field} =  [".$formattedString."]; \n \n";

                $static_functions .=  "
    public static function  ". getFunctionName('get',$field->field)."()
    {
        return self::\${$field->field};
    }

    public static function  ". getFunctionName('decode',$field->field)."(\$key=null)
    {
        return \$key ? (self::".getFunctionName('get',$field->field)."()[\$key]??null) : null;
    }";
            }
            // Write new content to the file
            $newContent = <<<PHP
                <?php

                namespace App\Models;

                use Illuminate\Database\Eloquent\Model;

                class {$modelName} extends Model
                {
                    protected \$table = '{$tableName}';
                    
                    protected \$fillable = [
                        {$fillable}
                    ];

                    protected \$appends = [
                        {$appends}
                    ];

                    protected \$cast = [
                        {$cast}
                    ];

                    {$custom_attributes}

                    {$static_arrays}

                    {$static_functions}

                    {$relations}

                    {$functions}

                    // Add your custom methods and properties here
                }
                PHP;

            // Write the new content into the model file
            $filesystem->put($modelPath, $newContent);

        }
        $booleanFieldsString = implode(',', $booleanFields);
        if ($filesystem->exists($controllerPath)) {
            $controllerContent = <<<PHP
            <?php

            namespace App\Http\Controllers;

            use App\Models\\{$modelName};
            use Illuminate\Http\Request;

            class {$controllerName} extends Controller
            {
                private \$booleanFields = [];

                public function __construct()
                {
                    \$this->booleanFields = [{$booleanFieldsString}];
                }

                public function index()
                {
                    \$items = {$modelName}::all();
                    return view('{$name}.index', compact('items'));
                }

                public function create()
                {
                    return view('{$name}.create');
                }

                public function store(Request \$request)
                {
                    // \$data = \$request->validate([
                    //     'name' => 'required|string|max:255',
                    //     'description' => 'nullable|string',
                    //     'type' => 'required|string',
                    //     'selections' => 'required|json',
                    //     'readonly' => 'required|boolean',
                    //     'disabled' => 'required|boolean',
                    // ]);

                    
                   
                    \$data = \$request->all();
                    foreach(\$this->booleanFields as \$field){
                        \$data[\$field] = isset(\$data[\$field]) ? true : false;
                    }
                    {$modelName}::create(\$data);

                    return redirect()->route('{$name}.index');
                }
                
                public function show({$modelName} \${$model_parm})
                {
                    return view('{$name}.view',compact('{$model_parm}'));
                }

                public function edit({$modelName} \${$model_parm})
                {
                    return view('{$name}.edit', compact('{$model_parm}'));
                }

                public function update(Request \$request, {$modelName} \${$model_parm})
                {
                    // \$data = \$request->validate([
                    //     'name' => 'required|string|max:255',
                    //     'description' => 'nullable|string',
                    //     'type' => 'required|string',
                    //     'selections' => 'required|json',
                    //     'readonly' => 'required|boolean',
                    //     'disabled' => 'required|boolean',
                    // ]);

                    \$data = \$request->all();
                    foreach(\$this->booleanFields as \$field){
                        \$data[\$field] = isset(\$data[\$field]) ? true : false;
                    }

                    \${$model_parm}->update(\$data);

                    return redirect()->route('{$name}.index');
                }

                public function destroy({$modelName} \${$model_parm})
                {
                    \${$model_parm}->delete();

                    return redirect()->route('{$name}.index');
                }
            }
            PHP;
            $filesystem->put($controllerPath, $controllerContent);
        }

        if ($filesystem->exists($migrationPath)) {
            $fields = '';
            foreach($form->fields as $field){
                if($field->type=='TEXT'){
                    $fields = $fields."\$table->string('".$field->field."');\r\n";
                }
                else if($field->type=='TEXTAREA'){
                    $fields = $fields."\$table->text('".$field->field."');\r\n";
                }
                else if($field->type=='NUMBER'){
                    $fields = $fields."\$table->integer('".$field->field."');\r\n";
                }
                else if($field->type=='BOOLEAN'){
                    $fields = $fields."\$table->boolean('".$field->field."')->default(false);\r\n";
                }
                else if($field->type=='SELECTION'){
                    $quotedSelections = array_map(fn($key) => "'$key'", array_keys($field->selections));
                    $selectionKeysString = implode(',', $quotedSelections);
                    $fields = $fields."\$table->enum('".$field->field."',[".$selectionKeysString."]);\r\n";
                }
                else if($field->type=='BELONGSTO'){
                    $fields = $fields."\$table->foreignId('".$field->field."')->constrained('".getTableName($field->related_model)."')->onDelete('cascade');;\r\n";
                }
            }

            $migrationContent = <<<PHP
                <?php

                use Illuminate\Database\Migrations\Migration;
                use Illuminate\Database\Schema\Blueprint;
                use Illuminate\Support\Facades\Schema;

                return new class extends Migration
                {
                    public function up()
                    {
                        Schema::dropIfExists('{$tableName}');
                        Schema::create('{$tableName}', function (Blueprint \$table) {
                            \$table->id();
                            {$fields}
                            \$table->timestamps();
                        });
                    }

                    public function down()
                    {
                        Schema::dropIfExists('{$tableName}');
                    }
                };
                PHP;
            $filesystem->put($migrationPath, $migrationContent);
        }


        if (!$filesystem->exists($viewPath)) {
            $filesystem->makeDirectory($viewPath, 0755, true);
        }

        $filteredFields = $form->fields->filter(fn($field) => $field->type !== 'BOOLEAN');
        $ths = '';
        foreach($filteredFields as $field){
            if($field->type != 'BOOLEAN'){
                $ths .= "<th class='px-4 py-2 text-left text-sm font-medium text-gray-500'>{$field->name}</th>\n";
            }
        }
        
        $tds = '';
        foreach($filteredFields as $field){
            $fieldName = $field->field;
            if($field->type != 'BOOLEAN'){
                if($field->type == 'SELECTION'){
                    $decodefn = getFunctionName('decode',$fieldName);
                    $tds .= "<td class='px-4 py-2 text-sm text-gray-900'>{{ $modelNamespace\\$modelName::$decodefn(\$item->$fieldName) }}</td>\n";
                }
                else if($field->type == 'BELONGSTO'){
                    $relationFieldName = getFunctionNameByClass($field->related_model).'->name';
                    $tds .= "<td class='px-4 py-2 text-sm text-gray-900'>{{ \$item->$relationFieldName ?? \$item->$fieldName ?? null }}</td>\n";
                }
                else{
                    $tds .= "<td class='px-4 py-2 text-sm text-gray-900'>{{ \$item->$fieldName }}</td>\n";
                }
            }
        }
        
        // Index View
        $indexViewContent = <<<BLADE
        @extends('k-frame.layouts.app')

        @section('content')
            <div class="text-center p-6">
            </div>
        @endsection

        @section('form')
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-center text-3xl font-bold text-gray-900 mb-6">{$modelName} List</h1>
            <div class="mb-4">
                <a href="{{ route('{$tableName}.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create
                </a>
            </div>
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class='px-4 py-2 text-left text-sm font-medium text-gray-500'>ID</th>
                            {$ths}
                            <th class='px-4 py-2 text-left text-sm font-medium text-gray-500'>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach(\$items as \$item)
                        <tr>
                            <td class='px-4 py-2 text-sm text-gray-900'>{{ \$item->id }}</td>
                            {$tds}
                            <td class='px-4 py-2 text-sm text-gray-900'>
                                <a href="{{ route('{$tableName}.show', \$item->id) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    View
                                </a>
                                <a href="{{ route('{$tableName}.edit', \$item->id) }}" class="inline-flex items-center px-3 py-1 ml-4 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    Edit
                                </a>
                                <form action="{{ route('{$tableName}.destroy', \$item->id) }}" method="POST" class="inline-block ml-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endsection
        BLADE;
        
        $filesystem->put("{$viewPath}/index.blade.php", $indexViewContent);

        //form 
        $form_fields = '';
        foreach ($form->fields as $field) {
            $field_name = $field->field;
            $field_label = $field->name;
        
            if($field->type == 'TEXT'){
                $form_fields .= <<<HTML
                <div class="mb-3">
                    <label for="{$field_name}" class="block text-sm font-medium text-gray-700">{$field_label}</label>
                    <input type="text" id="{$field_name}" name="{$field_name}" value="{{ old('{$field_name}', \${$model_parm}->{$field_name} ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required {{!\$editable ?' disabled':''}}>
                </div>
                HTML;
            }
            else if($field->type == 'NUMBER'){
                $form_fields .= <<<HTML
                <div class="mb-3">
                    <label for="{$field_name}" class="block text-sm font-medium text-gray-700">{$field_label}</label>
                    <input type="number" id="{$field_name}" name="{$field_name}" value="{{ old('{$field_name}', \${$model_parm}->{$field_name} ?? '') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required {{!\$editable ?' disabled':''}}>
                </div>
                HTML;
            }
            else if($field->type == 'TEXTAREA'){
                $form_fields .= <<<HTML
                <div class="mb-3">
                    <label for="{$field_name}" class="block text-sm font-medium text-gray-700">{$field_label}</label>
                    <textarea id="{$field_name}" name="{$field_name}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{!\$editable ?' disabled':''}}>{{ old('{$field_name}', \${$model_parm}->{$field_name} ?? '') }}</textarea>                
                </div>
                HTML;
            }
            else if($field->type == 'BOOLEAN'){
                $form_fields .= <<<HTML
                <div class="mb-3">
                    <div class="flex items-center">
                        <input type="checkbox" id="{$field_name}" name="{$field_name}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" value="1" {{ old('{$field_name}', \${$model_parm}->{$field_name} ?? '') ? ' checked' : '' }} {{!\$editable ?' disabled':''}}>
                        <label for="{$field_name}" class="ml-2 block text-sm font-medium text-gray-700">{$field_label}</label>
                    </div>
                </div>
                HTML;
            }
            else if($field->type == 'SELECTION'){
                $getfn = getFunctionName('get',$field->field);
                $form_fields .= <<<HTML
                <div class="mb-3">
                    <label for="{$field->field}" class="block text-sm font-medium text-gray-700">{$field_label}</label>
                    <select id="{$field->field}" name="{$field->field}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{!\$editable ?' disabled':''}}>
                    @foreach({$modelNamespace}\\{$modelName}::{$getfn}() as \$k => \$v) 
                        <option value="{{\$k}}"  {{ \$k == (old('{$field_name}', \${$model_parm}->{$field_name} ?? '')) ? ' selected' : '' }} >{{\$v}}</option>
                    @endforeach
                    </select>
                </div>
                HTML;
            }
            else if($field->type == 'BELONGSTO'){
                $form_fields .= <<<HTML
                <div class="mb-3">
                    <label for="{$field->field}" class="block text-sm font-medium text-gray-700">{$field_label}</label>
                    <select id="{$field->field}" name="{$field->field}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{!\$editable ?' disabled':''}}>
                        @foreach({$modelNamespace}\\{$field->related_model}::get()->pluck('name', 'id')->toArray() as \$k => \$v) 
                            <option value="{{\$k}}"  {{ \$k == (old('{$field_name}', \${$model_parm}->{$field_name} ?? '')) ? ' selected' : '' }} >{{\$v}}</option>
                        @endforeach
                    </select>
                </div>
                HTML;
            }
            
        }
        
        $formContent = <<<BLADE
        {$form_fields}
        BLADE;


        $filesystem->put("{$viewPath}/form.blade.php", $formContent);

        //edit blade
        $editViewContent = <<<BLADE
            @extends('k-frame.layouts.app')
            
            @section('content')
            <div class="text-center p-6">
            </div>
            @endsection

            @section('form')
            <div class="container">
                <form action="{{ route('{$name}.update', \${$model_parm}->id) }}" method="POST" class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md">
                    @csrf
                    @method('PUT')
                    <h1 class="text-center text-xl font-bold mb-4">Edit {$modelName} - {{\${$model_parm}->name ?? \${$model_parm}->id}}</h1>
                    @include('{$tableName}.form', ['editable' => true])
                    <div class="mt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save
                        </button>
                    </div>                
                </form>
            </div>
            @endsection
            BLADE;
                
        $filesystem->put("{$viewPath}/edit.blade.php", $editViewContent);

        //create blade
        $createViewContent = <<<BLADE
            @extends('k-frame.layouts.app')
            
            @section('content')
            <div class="text-center p-6">
            </div>
            @endsection

            @section('form')
            <div class="container mt-4">
                <form action="{{ route('{$name}.store') }}" method="POST" class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md">
                    @csrf
                    @method('POST')
                    <h1 class="text-center text-xl font-bold mb-4">Create {$modelName} - New</h1>
                    @include('{$tableName}.form', ['editable' => true])
                    <div class="mt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save
                        </button>
                    </div> 
                </form>
            </div>
            @endsection
            BLADE;
                
        $filesystem->put("{$viewPath}/create.blade.php", $createViewContent);

        //view blade
        $showViewContent = <<<BLADE
            @extends('k-frame.layouts.app')
            
            @section('content')
            <div class="text-center p-6">
            </div>
            @endsection

            @section('form')
            <div class="container mx-auto p-6 bg-white rounded-lg shadow-md max-w-lg">
                    <h1 class="text-center text-xl font-bold mb-4">View {$modelName} - {{\${$model_parm}->name ?? \${$model_parm}->id}}</h1>
                    @include('{$tableName}.form', ['editable' => false])

                <div class="mt-4 flex ">
                    <a href="{{ route('{$tableName}.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        List
                    </a>
                    <a href="{{ route('{$tableName}.edit', \${$model_parm}->id) }}" class="inline-flex items-center px-4 py-2 ml-4 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Edit
                    </a>
                </div>
            </div>
            @endsection
            BLADE;
                
        $filesystem->put("{$viewPath}/view.blade.php", $showViewContent);

        $controllerFQCN = "{$controllerNamespace}\\{$controllerName}";
        $routeString = "Route::resource('{$tableName}', '{$controllerFQCN}');";

        $webRoutesPath = base_path('routes/web.php');
        $webRoutesContent = file_get_contents($webRoutesPath);

        if (strpos($webRoutesContent, $routeString) === false) {
            file_put_contents($webRoutesPath, PHP_EOL . $routeString, FILE_APPEND);
        }

        if (file_exists($migrationPath)) {
            Artisan::call('migrate', [
                '--path' => str_replace(base_path(), '', $migrationPath),
            ]);
        }
        return redirect()->route('forms.index');
        
    }
}
