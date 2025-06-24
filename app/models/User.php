<?php

require_once BASE . 'app/models/Model.php';

class User extends Model
{
    protected string $pk_column = 'user';
    protected string $table = 'users';
}