<?php
namespace Refinery;

use Refinery\FormPost;


final class Refinery {

	private $post;

	private $formPost;

	private $context;

	private $steps = [];


	public function __construct($post, FormPost $formPost, FormContext $context) {

		$this->post = $post;

		$this->formPost = $formPost;

		$this->context = $context;
	}


	public function add($stepClass) {

		$this->steps[] = new $stepClass;

		return $this;
	}


	public function handle() {

		if (empty($this->post) && $this->context->step === 0) return FormResult::pass();

		$this->context->post = $this->post;

		if (! isset($this->steps[$this->context->step])) return FormResult::pass();

		$step = $this->steps[$this->context->step];

		$result = $step->handle($this->context);

		$this->context->notes = array_merge($this->context->notes, $result->notes);

		if (! $result->passed) return $result;

		if ($result->redirect) return $result;

		$this->context->step++;

		return $result;
	}


	public function fields() {

		if (! isset($this->steps[$this->context->step])) return [];

		return $this->steps[$this->context->step]->fields($this->context);
	}


	public function formPostValues() {

		return $this->formPost->encode($this->context);
	}


	public function postValues() {

		return array_merge($this->post, $this->context->data);
	}

}
