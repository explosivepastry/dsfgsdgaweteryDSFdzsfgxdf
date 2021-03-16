<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  Validate class
 *
 * 	TODO: Regex, Check IP Ban, "isvalid" MC username check
 */

class Validate {

<<<<<<< update/japanese
<<<<<<< update/japanese
    private $_message = null;
    private $_messages = array();
    private $_passed = false;
    private $_to_convert = array();
    private $_errors = array();
    
    /** @var DB */
    private $_db = null;
    
=======
=======
    private $_message = null;
>>>>>>> add generic message() function + add support for it
    private $_messages = array();
    private $_passed = false;
    private $_errors = array();
    private $_db = null;

>>>>>>> initial work (not working or tested)
    /**
     * Ensure this field is not empty
     */
    const REQUIRED = 'required';
<<<<<<< update/japanese
=======
    /**
     * Define minimum characters
     */
    const MIN = 'min';
    /**
     * Define max characters
     */
    const MAX = 'max';
    /**
     * Ensure provided value matches another
     */
    const MATCHES = 'matches';
    /**
     * Check the user has agreed to the terms and conditions
     */
    const AGREE = 'agree';
    /**
     * Check the value has not already been inputted in the database
     */
    const UNIQUE = 'unique';
    /**
     * Check if email is valid
     */
    const EMAIL = 'email';
    /**
     * Check that timezone is valid
     */
    const TIMEZONE = 'timezone';
    /**
     * Check that the specified user account is set as active (ie validated)
     */
    const IS_ACTIVE = 'isactive';
    /**
     * Check that the specified user account is not banned
     */
    const IS_BANNED = 'isbanned';
    /**
     * Check that the value is alphanumeric
     */
    const ALPHANUMERIC = 'alphanumeric';
    /**
     * Check that the value is numeric
     */
    const NUMERIC = 'numeric';
>>>>>>> initial work (not working or tested)

    /**
     * Define minimum characters
     */
    const MIN = 'min';

    /**
     * Define max characters
     */
    const MAX = 'max';

    /**
<<<<<<< update/japanese
     * Ensure provided value matches another
     */
    const MATCHES = 'matches';

    /**
     * Check the user has agreed to the terms and conditions
     */
    const AGREE = 'agree';

    /**
     * Check the value has not already been inputted in the database
     */
    const UNIQUE = 'unique';

    /**
     * Check if email is valid
     */
    const EMAIL = 'email';

    /**
     * Check that timezone is valid
     */
    const TIMEZONE = 'timezone';

    /**
     * Check that the specified user account is set as active (ie validated)
     */
    const IS_ACTIVE = 'isactive';

    /**
     * Check that the specified user account is not banned
     */
    const IS_BANNED = 'isbanned';

    /**
     * Check that the value is alphanumeric
     */
    const ALPHANUMERIC = 'alphanumeric';
    
    /**
     * Check that the value is numeric
     */
    const NUMERIC = 'numeric';

    /**
     * Create new `Validate` instance
=======
     * Create new Validate instance
>>>>>>> add generic message() function + add support for it
     */
    public function __construct() {
        // Connect to database for rules which need DB access
        try {
            $host = Config::get('mysql/host');
        } catch (Exception $e) {
            $host = null;
        }

        if (!empty($host)) {
            $this->_db = DB::getInstance();
        }
    }

    /**
     * Validate an array of inputs.
     * 
<<<<<<< update/japanese
     * @param array $source inputs (eg: $_POST)
     * @param array $items subset of inputs to be validated
     * @return Validate This instance of Validate.
=======
     * **Must** be called after messages() or message() for custom messages to apply
     * @param array $source inputs (eg: $_POST)
     * @param array $items subset of inputs to be validated
>>>>>>> add generic message() function + add support for it
     */
    public function check(array $source, array $items = array()) {

        // Loop through the items which need validating
        foreach ($items as $item => $rules) {

            // Loop through each validation rule for the set item
            foreach ($rules as $rule => $rule_value) {

                $value = trim($source[$item]);

                // Escape the item's contents just in case
                $item = Output::getClean($item);

                // Required rule
                if ($rule === Validate::REQUIRED && empty($value)) {
                    // The post array does not include this value, return an error
<<<<<<< update/japanese
<<<<<<< update/japanese
                    $this->addError([
                        'field' => $item,
                        'rule' => Validate::REQUIRED,
                        'fallback' => "{$item} is required."
                    ]);
=======
                    $this->addError($this->getMessage($item, Validate::REQUIRED, "{$item} is required"));
>>>>>>> add generic message() function + add support for it
                    continue;
                } 
                
                if (empty($value)) {
                    continue;
                }

                // The post array does include this value, continue validating
                switch ($rule) {

                    case Validate::MIN:
                        if (mb_strlen($value) < $rule_value) {
<<<<<<< update/japanese
                            $this->addError([
                                'field' => $item,
                                'rule' => Validate::MIN,
                                'fallback' => "{$item} must be a minimum of {$rule_value} characters."
                            ]);
=======
                            $this->addError($this->getMessage($item, Validate::MIN, "{$item} must be a minimum of {$rule_value} characters."));
>>>>>>> add generic message() function + add support for it
                        }
                        break;

                    case Validate::MAX:
                        if (mb_strlen($value) > $rule_value) {
<<<<<<< update/japanese
                            $this->addError([
                                'field' => $item,
                                'rule' => Validate::MAX,
                                'fallback' => "{$item} must be a maximum of {$rule_value} characters."
                            ]);
=======
                            $this->addError($this->getMessage($item, Validate::MAX, "{$item} must be a maximum of {$rule_value} characters."));
>>>>>>> add generic message() function + add support for it
                        }
                        break;

                    case Validate::MATCHES:
                        if ($value != $source[$rule_value]) {
<<<<<<< update/japanese
                            $this->addError([
                                'field' => $item,
                                'rule' => Validate::MATCHES,
                                'fallback' => "{$rule_value} must match {$item}."
                            ]);
=======
                            $this->addError($this->getMessage($item, Validate::MATCHES, "{$rule_value} must match {$item}."));
>>>>>>> add generic message() function + add support for it
                        }
                        break;

                    case Validate::AGREE:
                        if ($value != 1) {
<<<<<<< update/japanese
                            $this->addError([
                                'field' => $item,
                                'rule' => Validate::AGREE,
                                'fallback' => "You must agree to our terms and conditions in order to register."
                            ]);
=======
                            $this->addError($this->getMessage($item, Validate::AGREE, "You must agree to our terms and conditions in order to register."));
>>>>>>> add generic message() function + add support for it
                        }
                        break;

                    case Validate::UNIQUE:
                        $check = $this->_db->get($rule_value, array($item, '=', $value));
                        if ($check->count()) {
<<<<<<< update/japanese
                            $this->addError([
                                'field' => $item,
                                'rule' => Validate::UNIQUE,
                                'fallback' => "The {$rule_value}.{$item} {$value} already exists!"
                            ]);
=======
                            $this->addError($this->getMessage($item, Validate::UNIQUE, "The username/email {$item} already exists!"));
>>>>>>> add generic message() function + add support for it
                        }
                        break;

                    case Validate::EMAIL:
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
<<<<<<< update/japanese
                            $this->addError([
                                'field' => $item,
                                'rule' => Validate::EMAIL,
                                'fallback' => "{$value} is not a valid email."
                            ]);
=======
                            $this->addError($this->getMessage($item, Validate::EMAIL, "{$value} is not a valid email."));
>>>>>>> add generic message() function + add support for it
                        }
                        break;

                    case Validate::TIMEZONE:
<<<<<<< update/japanese
                        if (!in_array($value, DateTimeZone::listIdentifiers())) {
                            $this->addError([
                                'field' => $item,
                                'rule' => Validate::TIMEZONE,
                                'fallback' => "The timezone {$value} is invalid."
                            ]);
=======
                        if (!in_array($value, DateTimeZone::listIdentifiers(DateTimeZone::ALL))) {
                            $this->addError($this->getMessage($item, Validate::TIMEZONE, "The timezone {$item} is invalid."));
>>>>>>> add generic message() function + add support for it
                        }
                        break;

                    case Validate::IS_ACTIVE:
                        $check = $this->_db->get('users', array($item, '=', $value));
                        if (!$check->count()) {
                            break;
                        }

                        $isuseractive = $check->first()->active;
                        if ($isuseractive == 0) {
<<<<<<< update/japanese
                            $this->addError([
                                'field' => $item,
                                'rule' => Validate::IS_ACTIVE,
                                'fallback' => "That {$item} is inactive. Have you validated your account or requested a password reset?"
                            ]);
=======
                            $this->addError($this->getMessage($item, Validate::IS_ACTIVE, "That username is inactive. Have you validated your account or requested a password reset?"));
>>>>>>> add generic message() function + add support for it
                        }
                        break;

                    case Validate::IS_BANNED: 
                        $check = $this->_db->get('users', array($item, '=', $value));
                        if (!$check->count()) {
                            break;
                        }

                        $isuserbanned = $check->first()->isbanned;
                        if ($isuserbanned == 1) {
<<<<<<< update/japanese
                            $this->addError([
                                'field' => $item,
                                'rule' => Validate::IS_BANNED,
                                'fallback' => "The username {$value} is banned."
                            ]);
=======
                            $this->addError($this->getMessage($item, Validate::IS_BANNED, "The username {$item} is banned."));
>>>>>>> add generic message() function + add support for it
                        }
                        break;

                    case Validate::ALPHANUMERIC:
                        if (!ctype_alnum($value)) {
<<<<<<< update/japanese
                            $this->addError([
                                'field' => $item,
                                'rule' => Validate::ALPHANUMERIC,
                                'fallback' => "{$item} must be alphanumeric."
                            ]);
=======
                            $this->addError($this->getMessage($item, Validate::ALPHANUMERIC, "{$item} must be alphanumeric."));
>>>>>>> add generic message() function + add support for it
                        }
                        break;

                    case Validate::NUMERIC:
                        if (!is_numeric($value)) {
<<<<<<< update/japanese
                            $this->addError([
                                'field' => $item,
                                'rule' => Validate::NUMERIC,
                                'fallback' => "{$item} must be numeric."
                            ]);
                        }
                        break;
=======
                    $this->addError($item, "{$item} is required");

                } else if (!empty($value)) {
                    // The post array does include this value, continue validating
                    switch ($rule) {

                        case Validate::MIN:
                            if (mb_strlen($value) < $rule_value) {
                                $this->addError($this->getMessage($item, Validate::MIN, "{$item} must be a minimum of {$rule_value} characters."));
                            }
                            break;

                        case Validate::MAX:
                            if (mb_strlen($value) > $rule_value) {
                                $this->addError($this->getMessage($item, Validate::MAX, "{$item} must be a maximum of {$rule_value} characters."));
                            }
                            break;

                        case Validate::MATCHES:
                            if ($value != $source[$rule_value]) {
                                $this->addError($this->getMessage($item, Validate::MATCHES, "{$rule_value} must match {$item}."));
                            }
                            break;

                        case Validate::AGREE:
                            if ($value != 1) {
                                $this->addError($this->getMessage($item, Validate::AGREE, "You must agree to our terms and conditions in order to register."));
                            }
                            break;

                        case Validate::UNIQUE:
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if ($check->count()) {
                                $this->addError($this->getMessage($item, Validate::UNIQUE, "The username/email {$item} already exists!"));
                            }
                            break;

                        case Validate::EMAIL:
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError($this->getMessage($item, Validate::EMAIL, "{$value} is not a valid email."));
                            }
                            break;

                        case Validate::TIMEZONE:
                            if (!in_array($value, DateTimeZone::listIdentifiers(DateTimeZone::ALL))) {
                                $this->addError($this->getMessage($item, Validate::TIMEZONE, "The timezone {$item} is invalid."));
                            }
                            break;

                        case Validate::IS_ACTIVE:
                            $check = $this->_db->get('users', array($item, '=', $value));
                            if (!$check->count()) {
                                continue;
                            }

                            $isuseractive = $check->first()->active;
                            if ($isuseractive == 0) {
                                $this->addError($this->getMessage($item, Validate::IS_ACTIVE, "That username is inactive. Have you validated your account or requested a password reset?"));
                            }
                            break;

                        case Validate::IS_BANNED: 
                            $check = $this->_db->get('users', array($item, '=', $value));
                            if (!$check->count()) {
                                continue;
                            }

                            $isuserbanned = $check->first()->isbanned;
                            if ($isuserbanned == 1) {
                                $this->addError($this->getMessage($item, Validate::IS_BANNED, "The username {$item} is banned."));
                            }
                            break;

                        case Validate::ALPHANUMERIC:
                            if (!ctype_alnum($value)) {
                                $this->addError($this->getMessage($item, Validate::ALPHANUMERIC, "{$item} must be alphanumeric."));
                            }
                            break;

                        case Validate::NUMERIC:
                            if (!is_numeric($value)) {
                                $this->addError($this->getMessage($item, Validate::NUMERIC, "{$item} must be numeric."));
                            }
                            break;
                    }
>>>>>>> initial work (not working or tested)
=======
                            $this->addError($this->getMessage($item, Validate::NUMERIC, "{$item} must be numeric."));
                        }
                        break;
>>>>>>> add generic message() function + add support for it
                }
            }
        }

        if (empty($this->_to_convert)) {
            // Only return true if there are no errors
            $this->_passed = true;
        }

        return $this;
    }

    /**
<<<<<<< update/japanese
<<<<<<< update/japanese
     * Add generic message for any failures, specific `messages()` will override this.
     * 
     * @param string $message message to show if any failures occur.
     * @return Validate This instance of Validate.
=======
     * Add generic message for any failures, specific messages() will override this
     * @param string $message message to show if any failures occur
>>>>>>> add generic message() function + add support for it
     */
    public function message($message) {
        $this->_message = $message;
        return $this;
    }

    /**
<<<<<<< update/japanese
     * Add custom messages to this `Validate` instance.
     * 
     * @param array $messages array of input names and strings or arrays to use as messages.
     * @return Validate This instance of Validate.
     */
    public function messages(array $messages) {
=======
     * Add custom messages to this Validator
     * @param array $messages array of input names and strings or arrays to use as messages
     */
    public function messages($messages) {
>>>>>>> initial work (not working or tested)
=======
     * Add custom messages to this Validator
     * @param array $messages array of input names and strings or arrays to use as messages
     */
    public function messages(array $messages) {
>>>>>>> add generic message() function + add support for it
        $this->_messages = $messages;
        return $this;
    }

    /**
<<<<<<< update/japanese
     * Add an array of information to generate an error message to the $_to_convert array.
     * These errors will be translated in the `errors()` function later.
     * 
     * @param string $error message to add to error array
     */
    private function addError(array $error) {
        $this->_to_convert[] = $error;
    }

    /**
     * Get message for provided field, returning fallback message unless generic message is supplied.
     * 
     * @param string $field name of field to search for.
     * @param string $rule rule which check failed. should be from the constants defined above.
     * @param string $fallback fallback default message if custom message and generic message are not supplied.
     * @return Validate This instance of Validate.
     */
    private function getMessage($field, $rule, $fallback) {

        // No custom messages defined for this field
        if (!isset($this->_messages[$field])) {
            return $this->_message != null ? $this->_message : $fallback;
        }

        // Generic custom message for this field supplied - but not rule specific
        if (!is_array($this->_messages[$field])) {
            return $this->_messages[$field];
        }

        // Array of custom messages supplied, but none of their rules matches this rule
        if (!array_key_exists($rule, $this->_messages[$field])) {
            return $this->_message != null ? $this->_message : $fallback;
        }

        // Rule-specific custom message was supplied
        return $this->_messages[$field][$rule];
    }

    /**
     * Translate temp error information to their specific or generic or fallback messages and return.
     * 
     * @return array Any and all errors for this `Validate` instance.
=======
     * Add an error to the error array
     * @param string $error message to add to error array
     */
    private function addError($error) {

        // If there is no generic message() set, no need to worry about duplications
        if ($this->_message == null) {
            $this->_errors[] = $error;
            return;
        }

        // If this new error is the generic message AND it has not already been added, add it
        if ($error == $this->_message && !array_key_exists($this->_message, $this->_errors)) {
            $this->_errors[] = $this->_message;
        }
    }

    /**
     * Get message for provided field, returning fallback message unless generic message is supplied
     * @param string $field name of field to search for 
     * @param string $rule rule which check failed. should be from the constants defined above
     * @param string $fallback fallback default message if custom message and generic message are not supplied
     */
    private function getMessage($field, $rule, $fallback) {

        // No custom messages defined for this field
        if (!isset($this->_messages[$field])) {
            return $this->_message != null ? $this->_message : $fallback;
        }

        // Generic custom message supplied
        if (!is_array($this->_messages[$field])) {
            return $this->_messages[$field];
        }

        // Array of custom messages supplied, but none of their rules matches this rule
        if (!array_key_exists($rule, $this->_messages[$field])) {
            return $this->_message != null ? $this->_message : $fallback;
        }

        // Rule-specific custom message was supplied
        return $this->_messages[$field][$rule];
    }

    /**
     * Get current errors, if any
     * @return array Any and all errors for this Validator
>>>>>>> initial work (not working or tested)
     */
    public function errors() {

        // If errors have already been translated, dont waste time redoing it
        if (!empty($this->_errors)) {
            return $this->_errors;
        }

        // Loop all errors to convert and get their custom messages
        foreach ($this->_to_convert as $error) {

            $message = $this->getMessage($error['field'], $error['rule'], $error['fallback']);

            // If there is no generic `message()` set or the translated message is not equal to generic message
            // we can continue without worrying about duplications
            if ($this->_message == null || $message != $this->_message && !in_array($message, $this->_errors)) {
                $this->_errors[] = $message;
                continue;
            }
        
            // If this new error is the generic message AND it has not already been added, add it
            if ($message == $this->_message && !in_array($this->_message, $this->_errors)) {
                $this->_errors[] = $this->_message;
                continue;
            }
        }

        return $this->_errors;
    }

    /**
<<<<<<< update/japanese
     * Get if this `Validate` instance passed.
     * 
     * @return bool whether this Validate passed or not.
=======
     * Get if this Validator passed
     * @return bool whether this Validator passed or not
>>>>>>> initial work (not working or tested)
     */
    public function passed() {
        return $this->_passed;
    }

}
