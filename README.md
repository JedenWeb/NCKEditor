NCKEditor
=========

Implementation of CKEditor WYSIWYG editor as a Nette Framework component

Installation
------------

Best way to install is using [Composer](http://getcomposer.org):

```sh
$ composer require snakeaas/nckeditor
```

Usage
-----

Component itself behave like a default \Nette\Application\UI\Form form, but provides
some aditional methods.

Most important is method `addRichText` for adding CKEditor. 

**Example:**


```php

protected function createComponentEditor() {

	$editor = new \snakeaas\NCKEditor\NCKEditor(__DIR__  . '/../../www');

	$editor->addText('title', 'Nadpis');
	$editor->addRichText('editor', 'Text');
	
	$editor->addSubmit('save', 'Save');
	
	$editor->onSuccess[] = function (\Nette\Application\UI\Form $form) {
		// ... process values
	};

	return $editor;
}

```
