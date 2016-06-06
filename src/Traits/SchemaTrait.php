<?php
namespace Kun\Generator\Traits;
/**
 * Class SchemaParser with modifications by Fernando
 * @package Laralib\L5scaffold\Migrations
 * @author Jeffrey Way <jeffrey@jeffrey-way.com>
 */
trait SchemaTrait
{
    /**
     * The parsed schema.
     *
     * @var array
     */
    private $schema = [];
    /**
     * Parse the command line migration schema.
     * Ex: name:string, age:integer:nullable
     *
     * @param  string $schema
     * @return array
     */
    public function parse($schema)
    {
        $fields = $this->splitIntoFields($schema);
        foreach ($fields as $field) {
            $segments = $this->parseSegments($field);
            $this->addField($segments);
        }
        return $this->schema;
    }
    /**
     * Add a field to the schema array.
     *
     * @param  array $field
     * @return $this
     */
    private function addField($field)
    {
        $this->schema[] = $field;
        return $this;
    }
    /**
     * Get an array of fields from the given schema.
     *
     * @param  string $schema
     * @return array
     */
    private function splitIntoFields($schema)
    {
        return preg_split('/,\s?(?![^()]*\))/', $schema);
    }
    /**
     * Get the segments of the schema field.
     *
     * @param  string $field
     * @return array
     */
    private function parseSegments($field)
    {
        $segments = explode(':', $field);
        $name = array_shift($segments);
        $type = array_shift($segments);

        return compact('name', 'type');
    }

    /**
     * Create the schema for the "up" method.
     *
     * @param  string $schema
     * @param  array $meta
     * @return string
     * @throws GeneratorException
     */
    private function createSchemaForUpMethod($schema, $path)
    {
        return $fields = $this->constructSchema($schema);
    }
    /**
     * Construct the syntax for a down field.
     *
     * @param  array $schema
     * @param  array $meta
     * @return string
     * @throws GeneratorException
     */
    private function createSchemaForDownMethod($tableName)
    {
        return sprintf("Schema::drop('%s');", $tableName);

    }

    /**
     * Construct the schema fields.
     *
     * @param  array $schema
     * @param  string $direction
     * @return array
     */
    private function constructSchema($schema, $direction = 'Add')
    {
        if (!$schema) return '';
        $fields = array_map(function ($field) use ($direction) {
            $method = "{$direction}Column";
            return $this->$method($field);
        }, $schema);
        return implode("\n" . str_repeat(' ', 12), $fields);
    }

    private function addColumn($field)
    {
        $syntax = sprintf("\$table->%s('%s')", $field['type'], $field['name']);
        // If there are arguments for the schema type, like decimal('amount', 5, 2)
        // then we have to remember to work those in.
        $syntax .= ';';
        return $syntax;
    }
}
