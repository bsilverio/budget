<?php

return [


    'store_validation' => [
        'width.required' => "Store width is required.",
        'width.min' => "Store minimum width is :min.",
        'width.numeric' => "Store width should be numeric.",
        'height.required' => "Store height is required.",
        'height.min' => "Store minimum height is :min.",
        'height.numeric' => "Store height should be numeric.",
    ],
    'user_registration' => [
        'first_name.required' => 'First name is required.',
        'last_name.required' => 'Last name is required.',
        'email.required' => 'Email is required.',
        'email.unique' => 'Email used was already registered.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password has to be at least 6 characters.',
    ],
    'token_validation' => [
        'expired' => 'Token is expired.',
        'not_exist' => 'No request token provided.',
        'invalid' => "Token is invalid.",
        'user_not_exist' => "Token owner does not exist.",
    ],
    'robot_validation' => [
        'store_id.required' => 'Target store for Robot not provided.',
        'store_id.numeric' => 'Invalid store id.',
        'store_id.exists' => 'Store provided does not exist.',
        'x.required'     => 'X coordinate location is required.',
        'x.numeric'     => 'X coordinate should be a number.',
        'x.min'     => 'X coordinate should be greater than or equal to 0.',
        'x' => ['out_of_bound'  => "X coordinate should be within the shop's size."],
        'y.required'     => 'Y coordinate location is required.',
        'y.numeric'     => 'Y coordinate should be a number.',
        'y.min'     => 'Y coordinate should be greater than or equal to 0.',
        'y' => ['out_of_bound'  => "Y coordinate should be within the shop's size."],
        'heading.required'        => 'Robot heading is required.',
        'heading.in'        => 'Heading provided can only be: N,E,S,W.',
        'commands.required'   => 'Robot commands are required.',
        'commands.alpha'      => 'Robot commands can only contain the characters: L, R, M',
        'commands' => ['invalid' => 'Command has an invalid value.'],
        'store' => ['spot_taken' => 'A robot has already been positioned on that coordinate.']
    ],


];
