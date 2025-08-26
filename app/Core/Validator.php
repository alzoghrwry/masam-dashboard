<?php
namespace App\Core;

class Validator {

    private array $errors = [];

   
    
    public static function sanitizeString(?string $value): string {
        return htmlspecialchars(strip_tags(trim($value ?? '')), ENT_QUOTES, 'UTF-8');
    }

   
    public static function sanitizeInt($value): int {
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    

   
    public function email($value, string $field): void {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "الحقل {$field} ليس بريداً إلكترونياً صالحاً.";
        }
    }

   
    public function getErrors(): array {
        return $this->errors;
    }
}
