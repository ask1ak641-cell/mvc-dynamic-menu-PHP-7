<?php
/**
 * ═══════════════════════════════════════════════════════════════
 *  Validator Helper - Form validation sederhana
 * ═══════════════════════════════════════════════════════════════
 */
class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $field, string $label = ''): self
    {
        $value = $this->data[$field] ?? '';
        if (is_string($value)) {
            $value = trim($value);
        }
        if ($value === '' || $value === null) {
            $label = $label ?: ucfirst($field);
            $this->errors[$field][] = "{$label} wajib diisi.";
        }
        return $this;
    }

    public function email(string $field, string $label = ''): self
    {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $label = $label ?: ucfirst($field);
            $this->errors[$field][] = "{$label} harus berupa email yang valid.";
        }
        return $this;
    }

    public function minLength(string $field, int $min, string $label = ''): self
    {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && mb_strlen((string) $value) < $min) {
            $label = $label ?: ucfirst($field);
            $this->errors[$field][] = "{$label} minimal {$min} karakter.";
        }
        return $this;
    }

    public function maxLength(string $field, int $max, string $label = ''): self
    {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && mb_strlen((string) $value) > $max) {
            $label = $label ?: ucfirst($field);
            $this->errors[$field][] = "{$label} maksimal {$max} karakter.";
        }
        return $this;
    }

    public function numeric(string $field, string $label = ''): self
    {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && !is_numeric($value)) {
            $label = $label ?: ucfirst($field);
            $this->errors[$field][] = "{$label} harus berupa angka.";
        }
        return $this;
    }

    /**
     * Validasi alphanumeric (huruf, angka, underscore)
     */
    public function alphaNumeric(string $field, string $label = ''): self
    {
        $value = $this->data[$field] ?? '';
        if ($value !== '' && !preg_match('/^[a-zA-Z0-9_]+$/', $value)) {
            $label = $label ?: ucfirst($field);
            $this->errors[$field][] = "{$label} hanya boleh berisi huruf, angka, dan underscore.";
        }
        return $this;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        foreach ($this->errors as $messages) {
            return $messages[0] ?? null;
        }
        return null;
    }
}
