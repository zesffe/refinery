# refinery

**refinery** enables sequenced form pages for use in HTML forms with multiple pages.

Inspired (heavily) by `League\PipelineBuilder`.

## instructions

Build a form with multiple pages by (1) requiring everything:

```PHP

use Refinery\Refinery;
use Refinery\FormPost;

require $dir . '/../Refinery.php';
require $dir . '/../FormPost.php';
require $dir . '/../FormContext.php';
require $dir . '/../FormPage.php';
require $dir . '/../FormResult.php';
require $dir . '/../renderHelper.php';

```

by (2) defining the fields and handlers for each of your form's pages:

```PHP

// page #1
class InitializeForm implements FormPage {

	public function fields(FormContext $context): array {

		return ['name' => ['type'  => 'text', 'label' => 'name', ], ];
	}

	public function handle(FormContext $context): FormResult {

		$post = array_map('trim', $context->post); 

		extract($post);

		$notes = [];

		if (empty($name)) $notes[] = 'The `name` field is required.';

		if ($notes) return FormResult::fail($notes);

		$context->data = compact('name') + $context->data;

		return FormResult::pass(['Please fill the ContinueForm']);
	}
}


// page #2
class ContinueForm implements FormPage {

	public function fields(FormContext $context): array {

		return ['email' => ['type'  => 'text', 'label' => 'email address', ], ];
	}

	public function handle(FormContext $context): FormResult {

		$post = array_map('trim', $context->post); 

		extract($post);

		$notes = [];

		if (empty($email)) $notes[] = 'The `email` field is required.';

		if ($notes) return FormResult::fail($notes);

		$context->data = compact('email') + $context->data;

		return FormResult::pass(['Please continue to the FinalizeForm']);
	}
}


// page #3, a page potentially without any further fields at all!
class FinalizeForm implements FormPage {

	public function fields(FormContext $context): array {return []; }

	public function handle(FormContext $context): FormResult {return FormResult::pass(['Submit the form!']); }
}

```

and by (3) 

- (a) defining the `$secret` for hashin'
- (b) defining where the post vars be
- (c) combining (a) and (b) into a `$context`
- (d) defining a `new $refinery` using (c)
- (e) adding your form pages
- and (f) running `$refinery->handle()` using (b):

```PHP
$secret = md5('myPrecious');

$post = $_POST;

$formPost = new FormPost($secret);

$context = $formPost->decode($post['_postpack'] ?? null);

$refinery = new Refinery($post, $formPost, $context);

$refinery
    ->add(Pipelines\InitializeForm::class)
    ->add(Pipelines\ComposeBody::class)
    ->add(Pipelines\Send::class);

extract((array) $refinery->handle($post)); # (f)

if ($redirect) {header("Location: {$redirect}"); exit; }
```

For more information, please try the example file `index.php`.
