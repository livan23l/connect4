<?php

enum GeneralErrorsEnum: int
{
    case USER_OR_PASSWORD_INCORRECT = 1;
    case USER_ALREADY_REGISTERED = 2;
    case CURRENT_PASSWORD_INCORRECT = 3;

    public function errorMessage()
    {
        return match ($this) {
            self::USER_OR_PASSWORD_INCORRECT => "The username or password is incorrect",
            self::USER_ALREADY_REGISTERED => "That username is already registered",
            self::CURRENT_PASSWORD_INCORRECT => "The current password is incorrect",
        };
    }
}
