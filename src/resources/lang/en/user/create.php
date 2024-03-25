<?php

return [
    "form" => [
        "name" => [
            "title" => "Name",
        ],
        "email" => [
            "title" => "Email",
        ],
        "invite" => [
            "title" => "Send an invitation email ?",
            "hint" => "If checked, you won't have to create the password. The application will send an invitation to the given email. The user will have to choose his own password."
        ],
        "role" => [
            "title" => "Role",
            "placeholder" => "-- Select a role --",
            'hint' => "The role can be changed later by an administrator."
        ],
        "team" => [
            "title" => "Team",
            "placeholder" => "-- Select a team --",
            'hint' => "The team can be changed later by an administrator or by the user itself."
        ],
        "password" => [
            "title" => "Password",
        ],
        "password_confirmation" => [
            "title" => "Confirm Password",
            "warning" => "The password must contain at least 8 characters in length, one lowercase letter, one uppercase letter, one digit",
        ],
  ]
];