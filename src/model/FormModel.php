<?php

declare(strict_types=1);

namespace Corephp\Model;

use Corephp\Helper\Service;
use DateTime;
use Exception;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * FormModel
 * -----------
 * FormModel
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Model
 */
abstract class FormModel extends Model
{
    const RULE_REQUIRED = 'required';
    const RULE_EMAIL = 'email';
    const RULE_URL = 'url';
    const RULE_IP = 'ip';
    const RULE_LENGTH = 'length';
    const RULE_MIN_LENGTH = 'min_length';
    const RULE_MAX_LENGTH = 'max_length';
    const RULE_MATCH_FIELD = 'match_field';
    const RULE_NOT_MATCH_FIELD = 'not_match_field';
    const RULE_MATCH = 'match';
    const RULE_NOT_MATCH = 'not_match';
    const RULE_CONTAINS = 'contains';
    const RULE_NOT_CONTAINS = 'not_contains';
    const RULE_STARTS_WITH = 'starts_with';
    const RULE_ENDS_WITH = 'ends_with';
    const RULE_NUMERIC = 'numeric';
    const RULE_IN_LIST = 'in_list';
    const RULE_IN_RANGE = 'in_range';
    const RULE_MAX = 'max';
    const RULE_MIN = 'min';
    const RULE_DATE = 'date';
    const RULE_TIME = 'time';
    const RULE_CAPTCHA = 'captcha';
    const RULE_PASSWORD = 'password';

    protected array $rules = [];
    protected array $messages = [
        'required' => 'Atribut {attribute} harus diisi',
        'email' => 'Atribut {attribute} harus berisi alamat surel yang valid',
        'url' => 'Atribut {attribute} harus berisi alamat url yang valid',
        'ip' => 'Atribut {attribute} harus berisi alamat ip yang valid',
        'length' => 'Atribut {attribute} harus berisi karakter dengan panjang {length}',
        'min_length' => 'Atribut {attribute} harus berisi karakter dengan panjang minimal {min_length}',
        'max_length' => 'Atribut {attribute} harus berisi karakter dengan panjang maksimal {max_length}',
        'match_field' => 'Atribut {attribute} harus berisi sama dengan isian pada atribute {match_field}',
        'not_match_field' => 'Atribut {attribute} harus berisi berbeda dengan isian pada atribute {not_match_field}',
        'match' => 'Atribut {attribute} harus berisi sama dengan isian pada {match}',
        'not_match' => 'Atribut {attribute} harus berbeda dengan isian pada {not_match}',
        'contains' => 'Atribut {attribute} harus mengandung isian {contains}',
        'not_contains' => 'Atribut {attribute} harus tidak mengandung isian {not_contains}',
        'starts_with' => 'Atribut {attribute} harus dimulai dengan isian {starts_with}',
        'ends_with' => 'Atribut {attribute} harus diakhiri dengan isian {ends_with}',
        'numeric' => 'Atribut {attribute} harus berisi angka',
        'in_list' => 'Atribut {attribute} harus berisi salah satu dari {in_list}',
        'in_range' => 'Atribut {attribute} harus berisi angka pada rentang {in_range}',
        'min' => 'Atribut {attribute} harus berisi angka minimal {min}',
        'max' => 'Atribut {attribute} harus berisi angka maksimal {max}',
        'date' => 'Atribute {attribute} harus berisi tanggal dengan format {date}',
        'time' => 'Atribute {attribute} harus berisi waktu dengan format {time}',
        'captcha' => 'Atribute {attribute} tidak sesuai',
        'password' => 'Atribute {attribute} harus berisi huruf besar, huruf kecil, angka, karakter khusus, dan panjangnya minimal 6 huruf'
    ];
    protected bool $skipValidation;
    protected array $errors = [];
    protected array $labels = [];
    protected bool $isEdit;
    protected string $name;
    protected string $defaultAttribute;
    protected bool $isCsrfEnabled;

    /**
     * __construct
     *
     * @param  mixed $name
     * @param  mixed $defaultAttribute
     * @param  mixed $isEdit
     * @return void
     */
    public function __construct(string $name, string $defaultAttribute, bool $isEdit = false)
    {
        $this->name = $name;
        $this->defaultAttribute = $defaultAttribute;
        $this->isEdit = $isEdit;
        $this->isCsrfEnabled = config('security.enable_csrf_token', false);
        $this->skipValidation = false;
    }
    /**
     * addProperty
     *
     * @param  mixed $property
     * @param  mixed $type
     * @param  mixed $rule
     * @param  mixed $label
     * @param  mixed $defaultValue
     * @return void
     */
    public function addProperty(string $property, string $type = self::TYPE_STRING, null|string|array $rule = null, null|string $label = null, mixed $defaultValue = null): void
    {
        $this->types[$property] = $type;
        $this->storage[$property] = $defaultValue;
        if (!empty($rule)) {
            $this->rules[$property] = $rule;
        }
        if (!empty($label)) {
            $this->labels[$property] = $label;
        }
    }
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * isEdit
     *
     * @return bool
     */
    public function isEdit(): bool
    {
        return $this->isEdit;
    }
    /**
     * isCsrfEnabled
     *
     * @return bool
     */
    public function isCsrfEnabled(): bool
    {
        return $this->isCsrfEnabled;
    }
    /**
     * setRule
     *
     * @param  mixed $attribute
     * @param  mixed $rule
     * @return void
     */
    public function setRule(string $attribute, mixed $rule): void
    {
        if (property_exists($this, $attribute)) {
            $this->rules[$attribute] = $rule;
        }
    }

    /**
     * setLabel
     *
     * @param  mixed $attribute
     * @param  mixed $label
     * @return void
     */
    public function setLabel(string $attribute, string $label): void
    {
        if (property_exists($this, $attribute)) {
            $this->labels[$attribute] = $label;
        }
    }

    /**
     * getLabel
     *
     * @param  mixed $attribute
     * @return string
     */
    public function getLabel(string $attribute): string
    {
        return $this->labels[$attribute] ?? $attribute;
    }


    /**
     * setMessage
     *
     * @param  mixed $rule
     * @param  mixed $message
     * @return void
     */
    public function setMessage(string $rule, string $message): void
    {
        $this->messages[$rule] = $message;
    }

    /**
     * skipValidation
     *
     * @param  mixed $skip
     * @return void
     */
    public function skipValidation(bool $skip = true): void
    {
        $this->skipValidation = $skip;
    }

    /**
     * fill and validate data from request GET and POST and with security csrf_token check
     *
     * @param  mixed $request
     * @return bool
     */
    public function fillAndValidateWith(ServerRequestInterface $request): bool
    {
        $data = Service::sanitize($request);
        
        return $this->fillAndValidate($data);
    }
    /**
     * fill and validate from array data with security csrf_token check
     *
     * @param  array $data
     * @return bool
     */
    public function fillAndValidate(array $data): bool
    {
        $this->fill($data);

        if ($this->isCsrfEnabled) {

            if ($this->skipValidation) {
                return true;
            }

            $csrf_token_name = config('security.csrf_token_name', 'csrf_token');
            $csrf_token = $data[$csrf_token_name] ?? '';
            if (Service::session()->validateCsrfToken($this->name, $csrf_token) === false) {
                $this->addError('Csrf token tidak tersedia/tidak valid');
                return false;
            }
        }

        return $this->validate();
    }
    /**
     * validate form without security csrf_token check
     *
     * @return bool
     */
    public function validate(): bool
    {
        if ($this->skipValidation) {
            return true;
        }

        foreach ($this->rules as $attr => $rule) {

            $val = $this->getProperty($attr, '');
            if (is_string($rule)) {
                $rule = [$rule];
            } elseif (is_array($rule)) {
                if (is_string($rule[0]) && $this->isRuleHasParams($rule[0])) {
                    $rule = [$rule];
                }
            }

            foreach ($rule as $innerRule) {

                if (is_array($innerRule)) {
                    $ruleName = array_shift($innerRule);
                    $ruleParam = $innerRule;
                } elseif (is_string($innerRule)) {
                    $ruleName = $innerRule;
                    $ruleParam = '';
                } else {
                    throw new InvalidArgumentException('Rule definition not valid.');
                }

                switch ($ruleName) {
                    case self::RULE_REQUIRED:
                        if (!$val) {
                            $this->addErrorForRule($ruleName, $attr);
                            return false;
                        }
                        break;
                    case self::RULE_CAPTCHA:
                        if (Service::session()->validateCaptcha($this->name, $val) === false) {
                            $this->addErrorForRule($ruleName, $attr);
                            return false;
                        }
                        break;
                    case self::RULE_EMAIL:
                        if (filter_var($val, FILTER_VALIDATE_EMAIL) === false) {
                            $this->addErrorForRule($ruleName, $attr);
                            return false;
                        }
                        break;
                    case self::RULE_URL:
                        if (filter_var($val, FILTER_VALIDATE_URL) === false) {
                            $this->addErrorForRule($ruleName, $attr);
                            return false;
                        }
                        break;
                    case self::RULE_IP:
                        if (filter_var($val, FILTER_VALIDATE_IP) === false) {
                            $this->addErrorForRule($ruleName, $attr);
                            return false;
                        }
                        break;
                    case self::RULE_PASSWORD:
                        $uppercase = preg_match('@[A-Z]@', $val);
                        $lowercase = preg_match('@[a-z]@', $val);
                        $number    = preg_match('@[0-9]@', $val);
                        $specialChars = preg_match('@[^\w]@', $val);
                        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($val) < 6) {
                            $this->addErrorForRule($ruleName, $attr);
                            return false;
                        }
                        break;
                    case self::RULE_LENGTH:
                        $param = $ruleParam[0];
                        if (strlen($val) !== intval($param)) {
                            $this->addErrorForRule($ruleName, $attr, $param);
                            return false;
                        }
                        break;
                    case self::RULE_MIN_LENGTH:
                        $param = $ruleParam[0];
                        if (strlen($val) < intval($param)) {
                            $this->addErrorForRule($ruleName, $attr, $param);
                            return false;
                        }
                        break;
                    case self::RULE_MAX_LENGTH:
                        $param = $ruleParam[0];
                        if (strlen($val) > intval($param)) {
                            $this->addErrorForRule($ruleName, $attr, $param);
                            return false;
                        }
                        break;
                    case self::RULE_MATCH_FIELD:
                        $param = $ruleParam[0];
                        if ($val !== $this->getProperty($param)) {
                            $this->addErrorForRule($ruleName, $attr, $this->getLabel($param));
                            return false;
                        }
                        break;
                    case self::RULE_NOT_MATCH_FIELD:
                        $param = $ruleParam[0];
                        if ($val === $this->getProperty($param)) {
                            $this->addErrorForRule($ruleName, $attr, $this->getLabel($param));
                            return false;
                        }
                        break;
                    case self::RULE_MATCH:
                        $param = $ruleParam[0];
                        if ($val !== $param) {
                            $this->addErrorForRule($ruleName, $attr, $param);
                            return false;
                        }
                        break;
                    case self::RULE_NOT_MATCH:
                        $param = $ruleParam[0];
                        if ($val === $param) {
                            $this->addErrorForRule($ruleName, $attr, $param);
                            return false;
                        }
                        break;
                    case self::RULE_CONTAINS:
                        $param = $ruleParam[0];
                        if (str_contains($val, $param) === false) {
                            $this->addErrorForRule($ruleName, $attr, $param);
                            return false;
                        }
                        break;
                    case self::RULE_NOT_CONTAINS:
                        $param = $ruleParam[0];
                        if (str_contains($val, $param) === true) {
                            $this->addErrorForRule($ruleName, $attr, $param);
                            return false;
                        }
                        break;
                    case self::RULE_STARTS_WITH:
                        $param = $ruleParam[0];
                        if (str_starts_with($val, $param) === false) {
                            $this->addErrorForRule($ruleName, $attr, $param);
                            return false;
                        }
                        break;
                    case self::RULE_ENDS_WITH:
                        $param = $ruleParam[0];
                        if (str_ends_with($val, $param) === false) {
                            $this->addErrorForRule($ruleName, $attr, $param);
                            return false;
                        }
                        break;
                    case self::RULE_NUMERIC:
                        if (is_numeric($val) === false) {
                            $this->addErrorForRule($ruleName, $attr);
                            return false;
                        }
                        break;
                    case self::RULE_IN_LIST:
                        $param = $ruleParam[0];
                        if (in_array($val, $param) === false) {
                            $this->addErrorForRule($ruleName, $attr, '[' . implode(' atau ', $param) . ']');
                            return false;
                        }
                        break;
                    case self::RULE_IN_RANGE:
                        $min = floatval($ruleParam[0]);
                        $max = floatval($ruleParam[1]);
                        $v = floatval($val);
                        if ($v > $max || $v < $min) {
                            $this->addErrorForRule($ruleName, $attr, '[' . strval($min) . ',' . strval($max) . ']');
                            return false;
                        }
                        break;
                    case self::RULE_MAX:
                        $max = $ruleParam[0];
                        if (floatval($val) > floatval($max)) {
                            $this->addErrorForRule($ruleName, $attr, $max);
                            return false;
                        }
                        break;
                    case self::RULE_MIN:
                        $min = $ruleParam[0];
                        if (floatval($val) < floatval($min)) {
                            $this->addErrorForRule($ruleName, $attr, $min);
                            return false;
                        }
                        break;
                    case self::RULE_DATE:
                        if (DateTime::createFromFormat('Y-m-d', $val) === false) {
                            $this->addErrorForRule($ruleName, $attr);
                            return false;
                        }
                        break;
                    case self::RULE_TIME:
                        if (strtotime($val) === false) {
                            $this->addErrorForRule($ruleName, $attr);
                            return false;
                        }
                        break;
                    default:
                        throw new Exception("Rule {$ruleName} for attribute {$attr} not found or configured properly.");
                }
            }
        }

        return !$this->hasError();
    }
    /**
     * isRuleHasParams
     *
     * @param  mixed $ruleName
     * @return bool
     */
    protected function isRuleHasParams(string $ruleName): bool
    {
        $ruleNameWithParams = [
            'length',
            'min_length',
            'max_length',
            'match_field',
            'not_match_field',
            'match',
            'not_match',
            'contains',
            'not_contains',
            'starts_with',
            'ends_with',
            'numeric',
            'in_list',
            'in_range',
            'min',
            'max',
            'date'
        ];
        return in_array($ruleName, $ruleNameWithParams);
    }
    /**
     * addErrorForRule
     *
     * @param  mixed $rule
     * @param  mixed $attribute
     * @param  mixed $param
     * @return void
     */
    protected function addErrorForRule(string $rule, string $attribute, $param = null): void
    {
        $message = $this->messages[$rule] ?? '';
        if (!empty($message)) {
            $message = str_replace("{attribute}", $this->getLabel($attribute), $message);
            $message = str_replace("{{$rule}}", strval($param ?? ''), $message);
        }
        $this->addError($message, $attribute);
    }

    /**
     * addError
     *
     * @param  mixed $message
     * @param  mixed $attribute
     * 
     * @return void
     */
    public function addError(string $message, ?string $attribute = null): void
    {
        $attribute = $attribute ?? $this->defaultAttribute;
        $this->errors[$attribute][] = $message;
    }

    /**
     * hasError
     *
     * @param  mixed $attribute
     * @return bool
     */
    public function hasError(?string $attribute = null): bool
    {
        if (is_null($attribute)) {
            return !empty($this->errors);
        }

        return !empty($this->errors[$attribute]);
    }

    /**
     * firstError
     *
     * @param  mixed $attribute
     * @return string|array
     */
    public function firstError(?string $attribute = null): array|string
    {
        if ($attribute === null) {
            $message = [];
            foreach ($this->errors as $attr => $msg) {
                $message[] = $msg[0];
            }
            return $message;
        }
        return $this->errors[$attribute][0] ?? '';
    }

    /**
     * getError
     *
     * @param  mixed $attribute
     * @return array
     */
    public function getError(?string $attribute = null): array
    {
        if ($attribute === null) {
            $message = [];
            foreach ($this->errors as $attr => $msg) {
                $message[] = $msg;
            }
            return $message;
        }

        return $this->errors[$attribute] ?? [];
    }
}
