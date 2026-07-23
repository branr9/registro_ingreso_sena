<?php
/**
 * Clase de Validación
 * Valida datos de entrada con reglas personalizadas
 */

class Validator
{
    private array $data;
    private array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Validar datos según reglas
     * 
     * @param array $rules Ejemplo: ['email' => 'required|email', 'edad' => 'required|numeric|min:18']
     * @return bool
     */
    public function validate(array $rules): bool
    {
        foreach ($rules as $field => $ruleString) {
            $value = $this->data[$field] ?? null;
            $ruleList = explode('|', $ruleString);

            foreach ($ruleList as $rule) {
                // Reglas con parámetros: min:5, max:100, etc.
                $parts = explode(':', $rule, 2);
                $ruleName = $parts[0];
                $ruleParam = $parts[1] ?? null;

                // Aplicar regla
                $method = 'validate' . ucfirst($ruleName);
                if (method_exists($this, $method)) {
                    $this->$method($field, $value, $ruleParam);
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Obtener errores
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Obtener primer error
     */
    public function firstError(): ?string
    {
        return !empty($this->errors) ? reset($this->errors) : null;
    }

    /**
     * Agregar error personalizado
     */
    private function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    // ========================================
    // REGLAS DE VALIDACIÓN
    // ========================================

    protected function validateRequired(string $field, $value, $param = null): void
    {
        if (empty($value) && $value !== '0') {
            $this->addError($field, "El campo {$field} es obligatorio.");
        }
    }

    protected function validateEmail(string $field, $value, $param = null): void
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "El campo {$field} debe ser un email válido.");
        }
    }

    protected function validateNumeric(string $field, $value, $param = null): void
    {
        if (!empty($value) && !is_numeric($value)) {
            $this->addError($field, "El campo {$field} debe ser numérico.");
        }
    }

    protected function validateMin(string $field, $value, $param = null): void
    {
        if (!empty($value) && is_string($value) && strlen($value) < (int)$param) {
            $this->addError($field, "El campo {$field} debe tener al menos {$param} caracteres.");
        } elseif (is_numeric($value) && $value < (int)$param) {
            $this->addError($field, "El campo {$field} debe ser mayor o igual a {$param}.");
        }
    }

    protected function validateMax(string $field, $value, $param = null): void
    {
        if (!empty($value) && is_string($value) && strlen($value) > (int)$param) {
            $this->addError($field, "El campo {$field} no puede exceder {$param} caracteres.");
        } elseif (is_numeric($value) && $value > (int)$param) {
            $this->addError($field, "El campo {$field} no puede ser mayor a {$param}.");
        }
    }

    protected function validateAlpha(string $field, $value, $param = null): void
    {
        if (!empty($value) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $value)) {
            $this->addError($field, "El campo {$field} solo puede contener letras.");
        }
    }

    protected function validateAlphanumeric(string $field, $value, $param = null): void
    {
        if (!empty($value) && !preg_match('/^[a-zA-Z0-9]+$/', $value)) {
            $this->addError($field, "El campo {$field} solo puede contener letras y números.");
        }
    }

    protected function validateUnique(string $field, $value, $param = null): void
    {
        // Formato: unique:tabla,columna,id_excluir
        if (empty($value) || empty($param)) {
            return;
        }

        $parts = explode(',', $param);
        $table = $parts[0] ?? '';
        $column = $parts[1] ?? $field;
        $excludeId = $parts[2] ?? null;

        if (empty($table)) {
            return;
        }

        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";
        
        if ($excludeId) {
            $sql .= " AND id != :excludeId";
            $result = $db->fetchOne($sql, ['value' => $value, 'excludeId' => $excludeId]);
        } else {
            $result = $db->fetchOne($sql, ['value' => $value]);
        }

        if ($result && $result['count'] > 0) {
            $this->addError($field, "El valor '{$value}' ya está registrado en el sistema.");
        }
    }

    protected function validateIn(string $field, $value, $param = null): void
    {
        if (empty($value) || empty($param)) {
            return;
        }

        $allowedValues = explode(',', $param);
        if (!in_array($value, $allowedValues)) {
            $this->addError($field, "El campo {$field} debe ser uno de: " . implode(', ', $allowedValues));
        }
    }

    protected function validateMatches(string $field, $value, $param = null): void
    {
        if (!empty($value) && isset($this->data[$param]) && $value !== $this->data[$param]) {
            $this->addError($field, "El campo {$field} no coincide con {$param}.");
        }
    }

    protected function validateDocument(string $field, $value, $param = null): void
    {
        // Validar documento: solo números y letras, min 6, max 20
        if (!empty($value)) {
            if (!preg_match('/^[A-Z0-9]{6,20}$/i', $value)) {
                $this->addError($field, "El documento debe contener entre 6 y 20 caracteres alfanuméricos.");
            }
        }
    }
}
