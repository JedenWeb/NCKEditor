<?php
/**
 * @author  Martin "Snake.AAS" Grossmann <grossmann.martin@ovacloud.net>
 * @created 7.10.13 10:11
 */

namespace snakeaas\NCKEditor;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use snakeaas\NCKEditor\Storage;

/**
 * Class NCKEditor
 *
 * @package snakeaas\NCKEditor
 *
 * @method \Nette\Forms\Form setAction($url) Sets form's action.
 * @method mixed getAction() Returns form's action.
 * @method \Nette\Forms\Form setMethod($method) Sets form's method.
 * @method string getMethod() Returns form's method.
 * @method void addProtection($message = null, integer $timeout = null) Cross-Site Request Forgery (CSRF) form protection.
 * @method \Nette\Forms\ControlGroup addGroup($caption = null, $setAsCurrent = TRUE) Adds fieldset group to the form.
 * @method void removeGroup($name) Removes fieldset group from form.
 * @method FormGroup[] getGroups() Returns all defined groups.
 * @method \Nette\Forms\ControlGroup getGroup($name) Returns the specified group.
 * @method \Nette\Forms\Form setTranslator(\Nette\Localization\ITranslator $translator = null) Sets translate adapter.
 * @method \Nette\Localization\ITranslator|null getTranslator() Returns translate adapter.
 * @method boolean isAnchored() Tells if the form is anchored.
 * @method \Nette\Forms\ISubmitterControl|false isSubmitted() Tells if the form was submitted.
 * @method boolean isSuccess() Tells if the form was submitted and successfully validated.
 * @method \Nette\Forms\Form setSubmittedBy(\Nette\Forms\ISubmitterControl $by = null) Sets the submittor control.
 * @method array getHttpData() Returns submitted HTTP data.
 * @method void fireEvents() Fires submit/click events.
 * @method \Nette\ArrayHash|array getValues($asArray = FALSE) Returns the values submitted by the form.
 * @method void addError($message) Adds error message to the list.
 * @method array getErrors() Returns validation errors.
 * @method boolean hasErrors()
 * @method void cleanErrors()
 * @method \Nette\Utils\Html getElementPrototype() Returns form's HTML element template.
 * @method \Nette\Forms\Form setRenderer(\Nette\Forms\IFormRenderer $renderer) Sets form renderer.
 * @method \Nette\Forms\IFormRenderer getRenderer() Returns form renderer.
 * @method boolean __toString() Renders form to string.
 * @method \Nette\Forms\Container setDefaults($values, $erase = FALSE) Fill-in with default values.
 * @method \Nette\Forms\Container setValues($values, $erase = FALSE) Fill-in with values.
 * @method boolean isValid() Is form valid?
 * @method void validate() Performs the server side validation.
 * @method \Nette\Forms\Container setCurrentGroup(\Nette\Forms\ControlGroup $group = null)
 * @method \Nette\Forms\ControlGroup getCurrentGroup() Returns current group.
 * @method ArrayIterator getControls() Iterates over all form controls.
 * @method \Nette\Forms\Controls\TextInput addText($name, $label = null, $cols = null, $maxLength = null) Adds single-line text input control to the form.
 * @method \Nette\Forms\Controls\TextInput addPassword($name, $label = null, $cols = null, $maxLength = null) Adds single-line text input control used for sensitive input such as passwords.
 * @method \Nette\Forms\Controls\TextArea addTextArea($name, $label = null, $cols = 40, $rows = 10) Adds multi-line text input control to the form.
 * @method \Nette\Forms\Controls\UploadControl addUpload($name, $label = null) Adds control that allows the user to upload files.
 * @method \Nette\Forms\Controls\HiddenField addHidden($name, $default = null) Adds hidden form control used to store a non-displayed value.
 * @method \Nette\Forms\Controls\Checkbox addCheckbox($name, $caption = null) Adds check box control to the form.
 * @method \Nette\Forms\Controls\RadioList addRadioList($name, $label = null, $items = null) Adds set of radio button controls to the form.
 * @method \Nette\Forms\Controls\SelectBox addSelect($name, $label = null, $items = null, $size = null) Adds select box control that allows single item selection.
 * @method \Nette\Forms\Controls\MultiSelectBox addMultiSelect($name, $label = null, $items = null, $size = null) Adds select box control that allows multiple item selection.
 * @method \Nette\Forms\Controls\SubmitButton addSubmit($name, $caption = null) Adds button used to submit form.
 * @method \Nette\Forms\Controls\Button addButton($name, $caption) Adds push buttons with no default behavior.
 * @method \Nette\Forms\Controls\ImageButton addImage($name, $src = null, $alt = null) Adds graphical button used to submit form.
 * @method \Nette\Forms\Container addContainer($name) Adds naming container to the form.
 */
class NCKEditor extends Control {

	/** @var Form */
	protected $form;

	/** @var array */
	protected $richTexts;

	/** @var Config */
	protected $configuration;

	/** @var  string */
	protected $templateName;

	/** @var IStorage */
	protected $storage;


	/**
	 * Initialize component
	 */
	public function __construct($wwwDir, $uploadDir = 'upload') {
		$this->form          = new Form();
		$this->richTexts     = array();
		$this->configuration = new Config();
		$this->templateName  = 'NCKEditor.latte';

		$this->storage = new Storage\FileStorage($wwwDir, $uploadDir);
	}


	/* ------------------ Form fields ------------------ */

	/**
	 * Adds rich text editor to the form
	 *
	 * @param string $name
	 * @param string $label
	 */
	public function addRichText($name, $label) {
		$this->form->addTextArea($name, $label);
		$this->richTexts[] = $name;
	}


	/**
	 * Mediates standard functions from \Nette\Application\UI\Form
	 *
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed|void
	 */
	public function __call($name, $arguments) {
		if (method_exists($this->form, $name)) {
			return call_user_func_array($this->form->$name, $arguments);
		}

		parent::__call($name, $arguments);
	}


	/**
	 * Mediates standard properties from \Nette\Application\UI\Form
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function &__get($name) {
		if (property_exists($this->form, $name)) {
			return $this->form->$name;
		}

		return parent::__get($name);
	}


	/* ------------------ Render component ------------------ */

	/**
	 * Render a component
	 */
	public function render() {
		$this->template->setFile(__DIR__ . '/templates/' . $this->templateName);

		$this->template->richTexts = $this->getRichTextNames();
		$this->template->config    = $this->configuration;

		$this->template->render();
	}


	protected function createComponentForm() {
		return $this->form;
	}


	/**
	 * Switching to Image browsing mode
	 */
	public function handleImageBrowse() {
		$this->templateName = 'ImageBrowser.latte';

		$this->template->filePath = $this->template->basePath . '/' . $this->storage->getBaseUrl();
		$this->template->images   = $this->storage->getImages();
		$this->template->setFile(__DIR__ . '/templates/' . $this->templateName);
		$this->template->render();
		$this->getPresenter()->terminate();
	}


	/**
	 * Uploads a new file
	 */
	public function handleUploadFile() {
		$this->templateName = 'Upload.latte';

		$this->storage->addFile($_FILES['upload']['tmp_name'], $_FILES['upload']['name']);

		$this->template->url     = $this->template->basePath . '/' . $this->storage->getBaseUrl() . '/' . $_FILES['upload']['name'];
		$this->template->message = 'Soubor byl nahrán';
		$this->template->setFile(__DIR__ . '/templates/' . $this->templateName);
		$this->template->render();
		$this->getPresenter()->terminate();
	}


	/* ------------------ Getters / Setters ------------------ */

	/**
	 * @return array
	 */
	public function getRichTextNames() {
		return $this->richTexts;
	}


	/**
	 * @return Config
	 */
	public function getConfiguration() {
		return $this->configuration;
	}


	/**
	 * @param Storage\IStorage $storage
	 */
	public function setStorage(Storage\IStorage $storage) {
		$this->storage = $storage;
	}


	/**
	 * @return Storage\IStorage
	 */
	public function getStorage() {
		return $this->storage;
	}


	/**
	 * @return Form
	 */
	public function getForm() {
		return $this->form;
	}
}
