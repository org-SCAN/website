<?php

namespace Tests\Feature;

use App\Models\ListDataType;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LivewireCreateListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if the Livewire component renders successfully.
     *
     * @return void
     */
    public function test_create_list_component_renders_correctly()
    {
        Livewire::test('create-list')
            ->assertStatus(200)
            ->assertViewIs('livewire.create-list');
    }

    /**
     * Test if the fields are initialized correctly on mount.
     *
     * @return void
     */
    public function test_fields_are_initialized_correctly_on_mount()
    {
        Livewire::test('create-list')
            ->assertSet('fields', [
                ['name' => '', 'data_type_id' => '', 'required' => false]
            ]);
    }

    /**
     * Test if a new field can be added successfully.
     *
     * @return void
     */
    public function test_can_add_a_new_field()
    {
        Livewire::test('create-list')
            ->call('addField')
            ->assertSet('fields', [
                ['name' => '', 'data_type_id' => '', 'required' => false],
                ['name' => '', 'data_type_id' => '', 'required' => false],
            ]);
    }

    /**
     * Test if a field can be removed successfully.
     *
     * @return void
     */
    public function test_can_remove_a_field()
    {
        Livewire::test('create-list')
            ->call('addField')
            ->call('removeField', 1)
            ->assertSet('fields', [
                ['name' => '', 'data_type_id' => '', 'required' => false]
            ]);
    }


    /**
     * Test removing a field that doesn't exist doesn't throw an error.
     *
     * @return void
     */
    public function test_removing_non_existent_field_does_not_fail()
    {
        Livewire::test('create-list')
            ->call('removeField', 1) // Removing a field that doesn't exist
            ->assertSet('fields', [
                ['name' => '', 'data_type_id' => '', 'required' => false]
            ]);
    }

    /**
     * Test if the component can handle adding and removing multiple fields.
     *
     * @return void
     */
    public function test_can_add_and_remove_multiple_fields()
    {
        Livewire::test('create-list')
            ->call('addField')
            ->call('addField')
            ->assertSet('fields', [
                ['name' => '', 'data_type_id' => '', 'required' => false],
                ['name' => '', 'data_type_id' => '', 'required' => false],
                ['name' => '', 'data_type_id' => '', 'required' => false],
            ])
            ->call('removeField', 1)
            ->assertSet('fields', [
                ['name' => '', 'data_type_id' => '', 'required' => false],
                ['name' => '', 'data_type_id' => '', 'required' => false],
            ]);
    }
}
