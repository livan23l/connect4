<?php

enum AlertMessagesEnum: int
{
    case PASSWORD_UPDATED = 1;
    case SESSION_CLOSED = 2;
    case ACCOUNT_DELETED = 3;
    case UNKNOWN_ERRROR = 4;

    public function message()
    {
        return match ($this) {
            self::PASSWORD_UPDATED => "Password updated successfully",
            self::SESSION_CLOSED => "The session was successfully closed",
            self::ACCOUNT_DELETED => "The account was successfully deleted",
            self::UNKNOWN_ERRROR => "There was an unknown error",
        };
    }
}
