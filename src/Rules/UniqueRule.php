<?php

namespace Mousav1\Validify\Rules;

class UniqueRule extends Rule
{
    protected string $table;
    protected string $column;

    public function __construct(string $table, string $column)
    {
        $this->table = $table;
        $this->column = $column;
    }

    public function passes($field, $value, $data): bool
    {
        // فرض کنید یک متد استاتیک برای جستجو در دیتابیس وجود دارد
        // return !Database::table($this->table)->where($this->column, $value)->exists();
        return true;
    }

    public function message($field): string
    {
        return "{$field} must be unique.";
    }
}
