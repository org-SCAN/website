<?php
// resources/lang/en/fields/create.php

return [
    'add_new_field' => 'Add a new field',
    'field_title' => "Field's title",
    'field_title_placeholder' => "Example : Full Name",
    'field_title_hint' => "It'll be shown as title when the field is used.",
    'field_placeholder' => "Field's placeholder",
    'field_placeholder_placeholder' => "The placeholder is shown as an example when the field is asked (just like this)",
    'field_placeholder_hint' => "It'll be shown as an example when the field is asked.",
    'field_data_type' => "Field's Data type",
    'field_data_type_hint' => "It'll be used to store the datas.",
    'field_data_type_placeholder' =>  "-- Select the field type --",
    'field_data_type_warning' => "Be careful : you couldn't change this value later",


    'field_importance' => 'Choose the field weight',
    'field_importance_hint' => "It will be used to compute the duplication.",

    'field_required' => "Field's requirement state",
    'field_required_hint' => "Define the field's requirement state.",
    'field_required_placeholder' => "-- Select the field requirement --",
    'field_required_warning' => "Due to deployment conditions, you can't define the field as required",

    'field_status' => "Field's activation status",
    'field_status_hint' => "Define where the field will be deployed.",
    'field_status_placeholder' => "-- Select the field status --",
    'field_status_warning' => "Be careful, if the status is set to 'Disabled', the field won't be shown.",


    'field_best_descriptive_value' => "Is that the best descriptive value?",
    'field_best_descriptive_value_hint' => "If checked, it will be displayed in the Manage Items section as the main field.",
    'field_best_descriptive_value_warning' => "Be careful, there is only one best descriptive value per team.",

    'field_descriptive_value' => "Is that a descriptive value?",
    'field_descriptive_value_hint' => "If checked, it will be displayed in the Items section.",

    'field_validation_rules' => "Specific field's validation rules",
    'field_validation_rules_hint' => "Define the validation rules for this field. You can find the list of rules
                                <a href='https://laravel.com/docs/9.x/validation#available-validation-rules' class='text-blue-800'>here</a>",
    'field_validation_rules_placeholder' => "Example : required|email",
    'field_validation_rules_warning' => "Be careful, to use this section, you need to know the Laravel validation rules.",

];
