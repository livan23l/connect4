<?php

enum ValidateErrorsEnum: int
{
    case REQUIRED = 1;
    case STR = 2;
    case INT = 3;
    case FLOAT = 4;
    case NUMBER = 5;
    case MINLEN = 6;
    case MAXLEN = 7;
    case EMAIL = 8;
    case MIN = 9;
    case MAX = 10;
    case W_UPPER = 11;
    case W_LOWER = 12;
    case W_NUMBER = 13;
    case W_SPECIAL = 14;
    case CONFIRMED = 15;

    public function errorMessage($field, $restriction = 0)
    {
        $plural = ($restriction > 1) ? 's' : '';

        return match ($this) {
            self::REQUIRED => "#The ::$field field is required",
            self::STR => "#The ::$field field must be a valid string",
            self::INT => "#The ::$field field must be a valid integer",
            self::FLOAT => "#The ::$field field must be a valid decimal number",
            self::NUMBER => "#The ::$field field must be a valid number",
            self::MINLEN => "#The ::$field field must have at least :$restriction character" . $plural,
            self::MAXLEN => "#The ::$field field must have no more than :$restriction character" . $plural,
            self::EMAIL => "#The ::$field field must be a valid email",
            self::MIN => "#The ::$field field must be greater than :$restriction",
            self::MAX => "#The ::$field field must be less than :$restriction",
            self::W_UPPER => "#The ::$field field must have at least :$restriction capital letter" . $plural,
            self::W_LOWER => "#The ::$field field must have at least :$restriction lowercase letter" . $plural,
            self::W_NUMBER => "#The ::$field field must have at least :$restriction number" . $plural,
            self::W_SPECIAL => "#The ::$field field must have at least :$restriction special character" . $plural,
            self::CONFIRMED => "#The ::$field field does not match its confirmation field.",
        };
    }
}
