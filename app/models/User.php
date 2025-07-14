<?php

require_once BASE . 'app/models/Model.php';

class User extends Model
{
    protected string $table = 'users';
    protected string $pk_column = 'username';
}