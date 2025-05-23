<?php

namespace A909M\FilamentGenerateHelpers\Commands\Concerns;

use Illuminate\Support\Str;

trait CanGenerateTablesHelper
{
    protected function getResourceTableColumns(string $model, bool $isFunCall = false): string
    {
        $model = $this->getModel($model);

        if (blank($model)) {
            return '//';
        }

        $schema = $this->getModelSchema($model);
        $table = $this->getModelTable($model);

        $columns = [];

        foreach ($schema->getColumns($table) as $column) {
            if ($column['auto_increment']) {
                continue;
            }

            $type = $this->parseColumnType($column);

            if (in_array($type['name'], [
                'json',
                'text',
            ])) {
                continue;
            }

            $columnName = $column['name'];

            if (str($columnName)->endsWith([
                '_token',
            ])) {
                continue;
            }

            if (str($columnName)->contains([
                'password',
            ])) {
                continue;
            }

            if (str($columnName)->endsWith('_id')) {
                $guessedRelationshipName = $this->guessBelongsToRelationshipName($columnName, $model);

                if (filled($guessedRelationshipName)) {
                    $guessedRelationshipTitleColumnName = $this->guessBelongsToRelationshipTitleColumnName($columnName, app($model)->{$guessedRelationshipName}()->getModel()::class);

                    $columnName = "{$guessedRelationshipName}.{$guessedRelationshipTitleColumnName}";
                }
            }

            $columnData = [];

            if (in_array($columnName, [
                'id',
                'sku',
                'uuid',
            ])) {
                $columnData['label'] = [Str::upper($columnName)];
            }

            if ($type['name'] === 'boolean') {
                $columnData['type'] = \Filament\Tables\Columns\IconColumn::class;
                $columnData['boolean'] = [];
            } else {
                $columnData['type'] = match (true) {
                    $columnName === 'image', str($columnName)->startsWith('image_'), str($columnName)->contains('_image_'), str($columnName)->endsWith('_image') => \Filament\Tables\Columns\ImageColumn::class,
                    default => \Filament\Tables\Columns\TextColumn::class,
                };

                if (in_array($type['name'], [
                    'string',
                    'char',
                ]) && ($columnData['type'] === \Filament\Tables\Columns\TextColumn::class)) {
                    $columnData['searchable'] = [];
                }

                if (in_array($type['name'], [
                    'date',
                ])) {
                    $columnData['date'] = [];
                    $columnData['sortable'] = [];
                }

                if (in_array($type['name'], [
                    'datetime',
                    'timestamp',
                ])) {
                    $columnData['dateTime'] = [];
                    $columnData['sortable'] = [];
                }

                if (in_array($type['name'], [
                    'integer',
                    'decimal',
                    'float',
                    'double',
                    'money',
                ])) {
                    $columnData[in_array($columnName, [
                        'cost',
                        'money',
                        'price',
                    ]) || $type['name'] === 'money' ? 'money' : 'numeric'] = [];
                    $columnData['sortable'] = [];
                }
            }

            if (in_array($columnName, [
                'created_at',
                'updated_at',
                'deleted_at',
            ])) {
                $columnData['toggleable'] = ['isToggledHiddenByDefault' => true];
            }

            $columns[$columnName] = $columnData;
        }

        $output = count($columns) ? '' : '//';
        if ($isFunCall) {
            // code...
            foreach ($columns as $columnName => $columnData) {
                $baseColumnName = Str::before($columnName, '.');
                $functionName = Str::camel("{$baseColumnName}Column");
                $output .= "static::{$functionName}(),";
                $output .= PHP_EOL;
            }

            return $this->indentString($output, 2);
        } else {
            // code...

            foreach ($columns as $columnName => $columnData) {
                // Constructor
                $Prototype = '\\'.(string) str($columnData['type']);
                $baseColumnName = Str::before($columnName, '.');
                $functionName = Str::camel("{$baseColumnName}Column");
                $output .= PHP_EOL;
                $output .= '/**'.PHP_EOL;
                $output .= " * {$functionName}".PHP_EOL;
                $output .= ' *'.PHP_EOL;
                $output .= " * @return {$Prototype}".PHP_EOL;
                $output .= '*/'.PHP_EOL;
                $output .= PHP_EOL;
                $output .= "public static function {$functionName}():";
                $output .= $Prototype;
                $output .= PHP_EOL.'{'.PHP_EOL;

                $output .= $this->indentString('return ');

                $output .= $Prototype;
                $output .= '::make(\'';
                $output .= $columnName;
                $output .= '\')';

                unset($columnData['type']);

                // Configuration
                foreach ($columnData as $methodName => $parameters) {
                    $output .= PHP_EOL;
                    // $output .= '    ->';
                    $output .= $this->indentString('->', 2);
                    $output .= $methodName;
                    $output .= '(';
                    $output .= collect($parameters)
                        ->map(function (mixed $parameterValue, int|string $parameterName): string {
                            $parameterValue = match (true) {
                                /** @phpstan-ignore-next-line */
                                is_bool($parameterValue) => $parameterValue ? 'true' : 'false',
                                /** @phpstan-ignore-next-line */
                                is_null($parameterValue) => 'null',
                                is_numeric($parameterValue) => $parameterValue,
                                default => "'{$parameterValue}'",
                            };

                            if (is_numeric($parameterName)) {
                                return $parameterValue;
                            }

                            return "{$parameterName}: {$parameterValue}";
                        })
                        ->implode(', ');
                    $output .= ')';
                }
                // Termination
                $output .= ';'.PHP_EOL.'}'.PHP_EOL.PHP_EOL;
            }
        }

        return $output;
    }
}
