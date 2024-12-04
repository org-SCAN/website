<?php

namespace App\Imports;

use App\Http\Requests\StoreRefugeeRequest;
use App\Models\Field;
use App\Models\Refugee;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class RefugeesImport  implements ToCollection, WithHeadingRow, WithValidation
{
    // create a constructor to set the default fields, ...
    public function __construct()
    {
        $this->fields = Field::where('crew_id', auth()->user()->crew->id)->get();
        // keep only the title and the id. We don't need the rest. Make sure that the title is to lower case and str_slug().
        $this->field_conversion = $this->fields->mapWithKeys(function ($item) {
            return [Str::slug(strtolower($item->title)) => $item->id];
        })->toArray();

        // Search if one of the fields is the linked list. If so, we need to get the id of the linked list.
        $this->field_linked_list_content = [];
        foreach ($this->fields as $field) {
            if ($field->dataType->name == 'List') {
                $this->field_linked_list_content[$field->id] = array_flip($field->getLinkedListContent());
            }
        }

    }

    public function collection(Collection $rows)
    {

        foreach ($rows as $row)
        {
            $person = Refugee::create([
                'date' => $row["date"] ?? date('Y-m-d H:i',time()),
            ]);

            $ref = [];
            // attach the field (I should get the id of the field from the title)
            foreach($row as $key => $value){
                if (key_exists($key, $this->field_conversion) && !empty($value)) {
                    $field = $this->fields->find($this->field_conversion[$key]);
                    if (is_array($value)) {
                        $value = json_encode(array_filter($value));
                        // if value == [] then it is empty, so we don't need to store it
                        if ($value == "[]") {
                            continue;
                        }
                    }
                    if($field->dataType->name == "List"){ // if the field is a list, we need to get the id of the value
                        $value = $this->field_linked_list_content[$field->id][$value] ?? null;
                    }
                    $ref[$field->id] = ["value" => $value];
                }
            }
            // attach the fields to the person
            $person->fields()->attach($ref);
        }
    }

    public function rules(): array
    {
        // I need to get the fields that are required
        // Use the $rules defined in Request/StoreRefugeeRequest.php
        $rules = (new StoreRefugeeRequest())->rules();
        unset($rules[$this->fields->where('label', 'GDPR')->first()->id]);

        // replace the rules field id with the field title
        foreach($rules as $key => $rule){
            $field = $this->fields->find($key);
            $rules[Str::slug(Str::lower($field->title))] = $rule;
            if($field->dataType->name == "List"){
                $rules[Str::slug(Str::lower($field->title))] = Str::replace('uuid', 'string', $rule);
            }
            unset($rules[$key]);
        }
        return $rules;
    }
}
