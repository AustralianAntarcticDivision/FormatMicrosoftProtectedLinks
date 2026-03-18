<?php namespace ProcessWire;

/**
 * FormatMicrosoftProtectedLinks module config
 *
 * Hooks page save to find and replaces Microsoft protected links from
 * Outlook/Teams/Office with their original links
 *
 * @author Australian Antarctic Division
 * @copyright 2026 Commonwealth of Australia
 */

class FormatMicrosoftProtectedLinksConfig extends ModuleConfig {

	public function getDefaults() {
		return [
			'formatFields' => []
		];
	}

	public function getInputfields() {
		$inputfields = parent::getInputfields();
		$fields = array();

		foreach($this->wire('fields') as $field) {
			if (!$field->type instanceof FieldtypeText) continue; // only show text fields (includes text areas)
			$fields[$field->name] = $field;
		}

		uksort($fields, 'strnatcasecmp');

		$fieldset = $this->wire('modules')->get('InputfieldFieldset');
		$fieldset->label = $this->_('Fields to format');
		$inputfields->add($fieldset);

		/** @var InputfieldCheckboxes $f */
		$f = $this->wire('modules')->get('InputfieldCheckboxes');
		$f->name = 'formatFields';
		$f->label = 'Select which fields to format';
		$f->icon = 'cube';
		$f->optionColumns = 3;
		foreach($fields as $field) {
			$label = $field->name;
			$f->addOption($field->id, $label);
		}
		$fieldset->add($f);

		return $inputfields;
	}
}