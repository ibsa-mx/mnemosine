<?php

use Mnemosine\Field;
use Mnemosine\FieldGroup;
use Illuminate\Database\Seeder;

class FieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fieldGroups = [
            [
                'label' => 'Inventario',
                'active' => 1,
                'order' => 1,
                'module_id' => 1,
            ],
            [
                'label' => 'Investigación',
                'active' => 1,
                'order' => 1,
                'module_id' => 2,
            ],
            [
                'label' => 'Restauración',
                'active' => 1,
                'order' => 1,
                'module_id' => 3,
            ],
            [
                'label' => 'Medidas de la pieza',
                'active' => 1,
                'order' => 2,
                'module_id' => 1,
            ],
        ];

        foreach($fieldGroups as $el){
            if ($fieldGroupsRes = FieldGroup::create($el)) {
                $this->command->info('Se agrego el grupos de campos.');
            } else {
                $this->command->warn('No se agrego el grupos de campos.');
            }
        }

        $fields = [
            [
                'name' => 'origin_number',
                'label' => 'No. procedencia',
                'placeholder' => '',
                'edit' => 0,
                'required' => 1,
                'active' => 1,
                'summary_view' => 1,
                'order' => 1,
                'type' => 'text',
                'length' => NULL,
                'editable_in_modules' => '',
                'field_group_id' => 1,
                'catalog_id' => NULL,
            ],
            [
                'name' => 'inventory_number',
                'label' => 'No. Inventario',
                'placeholder' => '',
                'edit' => 0,
                'required' => 1,
                'active' => 1,
                'summary_view' => 1,
                'order' => 2,
                'type' => 'text',
                'length' => NULL,
                'editable_in_modules' => '',
                'field_group_id' => 1,
                'catalog_id' => NULL,
            ],
            [
                'name' => 'catalog_number',
                'label' => 'No. Catálogo',
                'placeholder' => '',
                'edit' => 0,
                'required' => 1,
                'active' => 1,
                'summary_view' => 1,
                'order' => 3,
                'type' => 'text',
                'length' => NULL,
                'editable_in_modules' => '',
                'field_group_id' => 1,
                'catalog_id' => NULL,
            ],
            [
                'name' => 'description_origin',
                'label' => 'Descripción',
                'placeholder' => 'Escriba una descripción de la pieza',
                'edit' => 0,
                'required' => 1,
                'active' => 1,
                'summary_view' => 1,
                'order' => 4,
                'type' => 'textarea',
                'length' => NULL,
                'editable_in_modules' => '',
                'field_group_id' => 1,
                'catalog_id' => NULL,
            ],
            [
                'name' => 'gender_id',
                'label' => 'Género',
                'placeholder' => '',
                'edit' => 0,
                'required' => 1,
                'active' => 1,
                'summary_view' => 0,
                'order' => 5,
                'type' => 'select',
                'length' => NULL,
                'editable_in_modules' => '',
                'field_group_id' => 1,
                'catalog_id' => NULL,
            ],
            [
                'name' => 'subgender_id',
                'label' => 'Subgénero',
                'placeholder' => '',
                'edit' => 0,
                'required' => 0,
                'active' => 1,
                'summary_view' => 0,
                'order' => 6,
                'type' => 'select',
                'length' => NULL,
                'editable_in_modules' => '',
                'field_group_id' => 1,
                'catalog_id' => NULL,
            ],
            [
                'name' => 'catalog_number',
                'label' => 'Ubicación',
                'placeholder' => '',
                'edit' => 1,
                'required' => 0,
                'active' => 1,
                'summary_view' => 0,
                'order' => 7,
                'type' => 'select',
                'length' => NULL,
                'editable_in_modules' => '',
                'field_group_id' => 1,
                'catalog_id' => 8,
            ],
            [
                'name' => 'type_object_id',
                'label' => 'Tipo de Objeto',
                'placeholder' => '',
                'edit' => 1,
                'required' => 0,
                'active' => 1,
                'summary_view' => 0,
                'order' => 8,
                'type' => 'select',
                'length' => NULL,
                'editable_in_modules' => '',
                'field_group_id' => 1,
                'catalog_id' => 7,
            ],
            [
                'name' => 'height',
                'label' => 'Alto',
                'placeholder' => '',
                'edit' => 1,
                'required' => 0,
                'active' => 1,
                'summary_view' => 0,
                'order' => 1,
                'type' => 'text',
                'length' => NULL,
                'editable_in_modules' => '3',
                'field_group_id' => 4,
                'catalog_id' => NULL,
            ],
            [
                'name' => 'width',
                'label' => 'Ancho',
                'placeholder' => '',
                'edit' => 1,
                'required' => 0,
                'active' => 1,
                'summary_view' => 0,
                'order' => 1,
                'type' => 'text',
                'length' => NULL,
                'editable_in_modules' => '3',
                'field_group_id' => 4,
                'catalog_id' => NULL,
            ],
            [
                'name' => 'depth',
                'label' => 'Profundo',
                'placeholder' => '',
                'edit' => 1,
                'required' => 0,
                'active' => 1,
                'summary_view' => 0,
                'order' => 1,
                'type' => 'text',
                'length' => NULL,
                'editable_in_modules' => '3',
                'field_group_id' => 4,
                'catalog_id' => NULL,
            ],
            [
                'name' => 'diameter',
                'label' => 'Diametro',
                'placeholder' => '',
                'edit' => 1,
                'required' => 0,
                'active' => 1,
                'summary_view' => 0,
                'order' => 1,
                'type' => 'text',
                'length' => NULL,
                'editable_in_modules' => '3',
                'field_group_id' => 4,
                'catalog_id' => NULL,
            ],
            [
                'name' => 'with_base',
                'label' => 'Marco/Base',
                'placeholder' => '',
                'edit' => 1,
                'required' => 0,
                'active' => 1,
                'summary_view' => 0,
                'order' => 5,
                'type' => 'checkbox',
                'length' => NULL,
                'editable_in_modules' => '3',
                'field_group_id' => 4,
                'catalog_id' => NULL,
            ],
        ];

        foreach($fields as $el){
            if ($fieldsRes = Field::create($el)) {
                $this->command->info('Se agrego el campo.');
            } else {
                $this->command->warn('No se agrego el campo.');
            }
        }
    }
}
