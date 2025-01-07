<?php

namespace A909M\FilamentGenerateHelpers\Commands;

use A909M\FilamentGenerateHelpers\Commands\Concerns\CanGenerateFormsHelper;
use A909M\FilamentGenerateHelpers\Commands\Concerns\CanGenerateTablesHelper;
use A909M\FilamentGenerateHelpers\Commands\Concerns\CanIndentStrings;
use A909M\FilamentGenerateHelpers\Commands\Concerns\CanManipulateFiles;
use A909M\FilamentGenerateHelpers\Commands\Concerns\CanReadModelSchemas;
use Illuminate\Console\Command;

class FilamentGenerateHelpersCommand extends Command
{
    use CanGenerateFormsHelper;
    use CanGenerateTablesHelper;
    use CanIndentStrings;
    use CanManipulateFiles;
    use CanReadModelSchemas;

    public $signature = 'filament-generate-helpers:run {name?} {--model-namespace=} {--F|force}';

    public $description = 'Create a new helper class. and generate the form and table columns methods on traits';

    public function handle(): int
    {
        $model = (string) str($this->argument('name') ?? text(
            label: 'What is the model name?',
            placeholder: 'BlogPost',
            required: true,
        ))
            ->studly()
            ->beforeLast('Resource')
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->studly()
            ->replace('/', '\\');

        if (blank($model)) {
            $model = 'Resource';
        }

        $modelNamespace = $this->option('model-namespace') ?? 'App\\Models';

        $modelClass = (string) str($model)->afterLast('\\');
        $modelSubNamespace = str($model)->contains('\\') ?
            (string) str($model)->beforeLast('\\') :
            '';
        $helper = "{$model}Helper";
        $helperClass = "{$modelClass}Helper";

        $path = app_path("Filament/Helpers/{$model}");
        $helperNamespace = "App\\Filament\\Helpers\\{$model}";
        $formFieldsTrait = "{$modelClass}FormFields";
        $tableColumnsTrait = "{$modelClass}TableColumns";

        $traits = $formFieldsTrait.','.$tableColumnsTrait;
        $baseHelperPath = "{$path}/{$helperClass}";
        $helperPath = "{$baseHelperPath}.php";
        $helperTraitDirectory = "{$path}/Traits";
        $formFieldsTraitPath = "{$helperTraitDirectory}/{$formFieldsTrait}.php";
        $tableColumnsTraitPath = "{$helperTraitDirectory}/{$tableColumnsTrait}.php";

        if (! $this->option('force') && $this->checkForCollision([
            $helperPath,
            $formFieldsTraitPath,
            $tableColumnsTraitPath,
        ])) {
            return static::INVALID;
        }

        $this->copyStubToApp('Helper', $helperPath, [
            'class' => $helperClass,
            'namespace' => $helperNamespace,
            'traits' => $traits,
            'formFieldsTrait' => $formFieldsTrait,
            'tableColumnsTrait' => $tableColumnsTrait,
            'FieldsCall' => $this->indentString($this->getResourceFormSchema(
                $modelNamespace.($modelSubNamespace !== '' ? "\\{$modelSubNamespace}" : '').'\\'.$modelClass,
                true
            ) ?: '//'),
            'ColumnsCall' => $this->indentString($this->getResourceTableColumns(
                $modelNamespace.($modelSubNamespace !== '' ? "\\{$modelSubNamespace}" : '').'\\'.$modelClass,
                true
            ) ?: '//'),
        ]);

        $this->copyStubToApp('FormFields', $formFieldsTraitPath, [
            'trait' => $formFieldsTrait,
            'namespace' => "{$helperNamespace}",
            'functions' => $this->indentString($this->getResourceFormSchema(
                $modelNamespace.($modelSubNamespace !== '' ? "\\{$modelSubNamespace}" : '').'\\'.$modelClass
            ) ?: '//'),
        ]);

        $this->copyStubToApp('TableColumns', $tableColumnsTraitPath, [
            'trait' => $tableColumnsTrait,
            'namespace' => "{$helperNamespace}",
            'functions' => $this->indentString($this->getResourceTableColumns(
                $modelNamespace.($modelSubNamespace !== '' ? "\\{$modelSubNamespace}" : '').'\\'.$modelClass
            ) ?: '//'),
        ]);

        $this->components->info("Filament Class helper [{$helperPath}] created successfully.");
        $this->components->info("Trait Form helper [{$formFieldsTraitPath}] created successfully.");
        $this->components->info("Trait Table helper [{$tableColumnsTraitPath}] created successfully.");

        return static::SUCCESS;
    }
}
