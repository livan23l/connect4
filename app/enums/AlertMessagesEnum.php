<?php

enum AlertMessagesEnum: int
{
    case PASSWORD_UPDATED = 1;

    public function message()
    {
        return match ($this) {
            self::PASSWORD_UPDATED => "Password updated successfully",
        };
    }
}
