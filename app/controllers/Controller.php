<?php

require_once BASE . 'app/enums/ValidateErrorsEnum.php';

class Controller
{
    protected array $parameters;
    protected array $request;
    protected array $errors = [];

    /**
     * Renders a view file with the given variables.
     *
     * @param string $view The name of the view file.
     * @param array $variables The variables to be extracted for the view.
     * @return string The rendered view content.
     */
    protected function view($view, $variables = [])
    {
        // Extract all the variables
        extract($variables);

        // Get and save the view
        ob_start();
        $view = str_replace('.', '/', $view);
        require_once BASE . "resources/views/$view.php";
        $file = ob_get_clean();  // Get the buffer data and close it

        // Return the view file
        return $file;
    }

    /**
     * Redirects the user to a specified route while storing error and old input
     * data in the session.
     *
     * @param string $route The route or URL to redirect to.
     * @param mixed $error Optional. Error message(s) to store in the session.
     * If not provided, $this->errors is used.
     * @param mixed $old Optional. Old form data to store in the session.
     * If not provided, $this->request is used.
     *
     * @return void
     */
    protected function redirect($route, $error = null, $old = null)
    {
        $_SESSION['error'] = $error ?? $this->errors;
        $_SESSION['old'] = $old ?? $this->request;
        header("Location: $route");
        exit;
    }

    /**
     * Sends an HTTP response with the specified status code.
     *
     * @param int  $code   The HTTP status code to send in the response.
     *
     * @return void
     */
    protected function response($code)
    {
        echo http_response_code($code);
        exit;
    }

    /**
     * Validates request fields based on provided validation rules.
     * Returns true if all validations pass; otherwise, false.
     * Validation errors are stored in $this->errors.
     * 
     * @param array $validations Associative array where keys are field names,
     * and values are validation rules separated by '|'. Example:
     * 
     * [
     *    'field1' => 'required|str|minlen:3',
     * 
     *    'field2' => 'int|max:100'
     * ]
     * 
     * Fields not present in the request are considered valid unless marked
     * 'required'.
     * 
     * Supported validation rules:
     * 1) required      - Field must be present and not empty.
     * 2) str           - Field must be a string.
     * 3) int           - Field must be an integer.
     * 4) float         - Field must be a decimal number (contain a dot).
     * 5) number        - Field must be numeric (int or float).
     * 6) minlen:val    - String length must be at least `val`.
     * 7) maxlen:val    - String length must be no more than `val`.
     * 8) email         - Field must be a valid email address.
     * 9) min:val       - Numeric value must be >= `val`.
     * 10) max:val      - Numeric value must be <= `val`.
     * 11) w_upper:val  - String must have at least uppercase `val`.
     * 12) w_lower:val  - String must have at least lowercase `val`.
     * 13) w_number:val - String must have at least `val` numbers.
     * 14) w_special:val- String must have at least `val` special characters.
     * 15) confirmed    - The value must be equal to `field_confirmation`.
     * 
     * @return bool True if all validations pass; false otherwise.
     */
    protected function validate($validations)
    {
        // Organize validations by type
        $regular_validations = [
            'required',
            'str',
            'int',
            'float',
            'number',
            'minlen',
            'maxlen',
            'email',
            'min',
            'max',
        ];
        $with_validations = [
            'w_upper',
            'w_lower',
            'w_number',
            'w_special',
        ];
        $other_validations = ['confirmed'];

        // Check all the validations
        $validated = true;  // Assume everything is correct
        foreach ($validations as $field => $validation) {
            // Separte all the validations
            $validation_array = explode('|', $validation);

            // Check if the field is not in the request array
            if (!isset($this->request[$field])) {
                // Check if it's required
                if (in_array('required', $validation_array)) {
                    // Add the error
                    $validated = false;
                    $this->errors[$field] = ValidateErrorsEnum::REQUIRED
                        ->errorMessage($field);
                }
                continue;
            }

            // Check all validations for the current field
            $value = $this->request[$field];
            foreach ($validation_array as $current_validation) {
                // Check if the validation has ':' to get the restriction
                $restriction = 0;
                $pos = strpos($current_validation, ':');
                if ($pos !== false) {
                    $restriction = (int)substr($current_validation, $pos + 1);
                    $current_validation = substr($current_validation, 0, $pos);
                }

                // Check the validation according to it's type
                $action = false;
                if (in_array($current_validation, $regular_validations)) {
                    $action = match ($current_validation) {
                        'required' => $value !== '' && $value !== null && $value !== [],
                        'str' => is_string($value),
                        'int' => filter_var($value, FILTER_VALIDATE_INT) !== false,
                        'float' => is_numeric($value) && strpos($value, '.') !== false,
                        'number' => is_numeric($value),
                        'minlen' => mb_strlen($value) >= $restriction,
                        'maxlen' => mb_strlen($value) <= $restriction,
                        'email' => filter_var($value, FILTER_VALIDATE_EMAIL) !== false,
                        'min' => (float)$value >= (float)$restriction,
                        'max' => (float)$value <= (float)$restriction,
                    };
                } elseif (in_array($current_validation, $with_validations)) {
                    // Get the pattern for the regular expression
                    $pattern = match ($current_validation) {
                        'w_upper' => '/[a-z]/',
                        'w_lower' => '/[A-Z]/',
                        'w_number' => '/[0-9]/',
                        'w_special' => '/[^a-zA-Z0-9]/',
                    };

                    // Check if matches correspond to the restriction
                    if (preg_match_all($pattern, $value, $matches)) {
                        $action = count($matches[0]) >= $restriction;
                    }
                } elseif (in_array($current_validation, $other_validations)) {
                    switch ($current_validation) {
                        case 'confirmed':
                            // Check if exist the 'field_confirmation' field and
                            // compare the values
                            if (
                                isset($this->request[$field . '_confirmation']) &&
                                $this->request[$field . '_confirmation'] == $value
                            ) {
                                $action = true;
                            }
                            break;
                    }
                }

                // Check if the action is false to set an error
                if (!$action) {
                    $validated = false;
                    $this->errors[$field] = ValidateErrorsEnum::{strtoupper($current_validation)}
                        ->errorMessage($field, $restriction);
                    break;  // Continue to the next field
                }
            }
        }

        return $validated;
    }

    /**
     * Controller constructor.
     *
     * @param array $parameters The route parameters.
     * @param array $request The request data.
     */
    public function __construct($parameters, $request)
    {
        $this->parameters = $parameters;
        $this->request = $request;
    }
}
