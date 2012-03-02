<?php

/**
 * Attendee form base class.
 *
 * @method Attendee getObject() Returns the current form's model object
 *
 * @package    web_app
 * @subpackage form
 * @author     Leonard Camacho <leonard.camacho@gmail.com>
 */
abstract class BaseAttendeeForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputText(),
      'lastname'   => new sfWidgetFormInputText(),
      'sex'        => new sfWidgetFormInputText(),
      'birth_year' => new sfWidgetFormInputText(),
      'email'      => new sfWidgetFormInputText(),
      'known_by'   => new sfWidgetFormInputText(),
      'comments'   => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorPropelChoice(array('model' => 'Attendee', 'column' => 'id', 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 50)),
      'lastname'   => new sfValidatorString(array('max_length' => 50)),
      'sex'        => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647)),
      'birth_year' => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647)),
      'email'      => new sfValidatorString(array('max_length' => 100)),
      'known_by'   => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'comments'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('attendee[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Attendee';
  }


}
