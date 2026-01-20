<?php
namespace Refinery;


final class FormResult {

    public $passed;

    public $redirect;

    public $notes;


    public function __construct($passed, $redirect = null, array $notes = []) {

        $this->passed = $passed;

        $this->redirect = $redirect;

        $this->notes = $notes;
    }


    public static function pass(array $notes = []) {

        return new self(true, null, $notes);
    }


    public static function fail(array $notes = []) {

        return new self(false, null, $notes);
    }


    public static function redirect($to, array $notes = []) {

        return new self(true, $to, $notes);
    }

}
