<?php
namespace Refinery;


interface FormPage {

    public function fields(FormContext $context): array;


    public function handle(FormContext $context): FormResult;

}
