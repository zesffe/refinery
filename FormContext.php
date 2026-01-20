<?php
namespace Refinery;


final class FormContext {

	public $step = 0;

	public $post = [];

	public $data = [];

	public $notes = [];


	public function __construct(array $post = [], $step = 0, array $data = [], array $notes = []) {

		$this->post = $post;

		$this->step = $step;

		$this->data = $data;

		$this->notes = $notes;
	}

}
