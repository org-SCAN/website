<?php

namespace Tests\Feature;


use App\Models\Field;
use App\Models\ListDataType;
use App\Models\Refugee;
use Livewire\Livewire;
use Tests\TestCase;

class LivewireFormShowTest extends TestCase
{
//    private array $dataType  = [
//        'short_text' => [
//            'html_data_type' => 'text',
//            'form_view' => 'livewire.forms.show.text',
//            'example' => 'A short example'
//        ],
//        'long_text' => [
//            'html_data_type' => 'textarea',
//            'form_view' => 'livewire.forms.show.text',
//            'example' => 'A long example that spans multiple lines'
//        ],
//        'number' => [
//            'html_data_type' => 'number',
//            'form_view' => 'livewire.forms.show.text',
//            'example' => '12345'
//        ],
//        'date' => [
//            'html_data_type' => 'date',
//            'form_view' => 'livewire.forms.show.text',
//            'example' => '2024-10-10'
//        ],
//        'yes_no' => [
//            'html_data_type' => 'checkbox',
//            'form_view' => 'livewire.forms.show.checkbox',
//            'example' => true
//        ],
//        'list' => [
//            'html_data_type' => 'select-dropdown',
//            'form_view' => 'livewire.forms.show.text',
//            'example' => 'Select one option from a list'
//        ],
//        'color' => [
//            'html_data_type' => 'color',
//            'form_view' => 'livewire.forms.show.color',
//            'example' => '#FF5733'
//        ],
//        'range' => [
//            'html_data_type' => 'range',
//            'form_view' => 'livewire.forms.show.range',
//            'example' => 50
//        ],
//        'coordinates' => [
//            'html_data_type' => 'coordinates',
//            'form_view' => 'livewire.forms.show.coordinates',
//            'example' => ['lat' => 48.8588443, 'long' => 2.2943506]
//        ],
//        'area' => [
//            'html_data_type' => 'area',
//            'form_view' => 'livewire.forms.show.area',
//            'example' => 'An example for an area selection'
//        ],
//    ];

//    public function test_form_view()
//    {
//        $refugee = Refugee::factory()->create();
//        $ref = [];
//        foreach ($this->dataType as $key => $value) {
//            $field = Field::factory()->create([
//                'data_type_id' => ListDataType::where('html_type', $value['html_data_type'])->first()->id
//            ]);
//            $ref[$field->id] = ["value" => $value['example']];
//        }
//        $refugee->fields()->attach($ref);
//
//        dd($refugee->fields);
//
//        Livewire::test('forms.show', ['field' => Field::factory()->create(['data_type_id' => ListDataType::where('html_type', 'text')->first()->id])])
//            ->assertStatus(200);
//    }

    public function test_form_view_text()
    {
        Livewire::test('forms.show', ['field' => Field::factory()->create(['data_type_id' => ListDataType::where('html_type', 'text')->first()->id])])
            ->assertStatus(200)
            ->assertViewIs('livewire.forms.show.text');
    }

    public function test_form_view_textarea()
    {
        Livewire::test('forms.show', ['field' => Field::factory()->create(['data_type_id' => ListDataType::where('html_type', 'textarea')->first()->id])])
            ->assertStatus(200)
            ->assertViewIs('livewire.forms.show.text');
    }
}
