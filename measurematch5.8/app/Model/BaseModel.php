<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Validator;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    /**
     * Error message bag
     *
     * @var Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Validation rules
     *
     * @var Array
     */
    protected static $rules = [];

    /**
     * Custom messages
     *
     * @var Array
     */
    protected static $messages = [];

    /**
     * Validator instance
     *
     * @var Illuminate\Validation\Validators
     */
    protected $validator;

    public function __construct(array $attributes = [], Validator $validator = null)
    {

        parent::__construct($attributes);
        $this->validator = $validator ?: \App::make('validator');
    }

    /**
     * @codeCoverageIgnore
     * Listen for save event
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            return $model->validate();
        });
    }

    /**
     * @codeCoverageIgnore
     * Validates current attributes against rules
     */
    public function validate()
    {
        $validator = Validator::make($this->attributes, static::$rules, static::$messages);
        if ($validator->fails()) {
            $this->setErrors($validator->errors());
            return false;
        }
        return true;
    }

    /**
     * @codeCoverageIgnore
     * Set error message bag
     *
     * @var Illuminate\Support\MessageBag
     */
    protected function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @codeCoverageIgnore
     * Retrieve error message bag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @codeCoverageIgnore
     * Inverse of wasSaved
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

}