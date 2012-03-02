<?php

/**
 * Speaker form base class.
 *
 * @method Speaker getObject() Returns the current form's model object
 *
 * @package    web_app
 * @subpackage form
 * @author     Leonard Camacho <leonard.camacho@gmail.com>
 */
abstract class BaseSpeakerForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInputText(),
      'lastname'    => new sfWidgetFormInputText(),
      'sex'         => new sfWidgetFormInputText(),
      'birth_year'  => new sfWidgetFormInputText(),
      'email'       => new sfWidgetFormInputText(),
      'contact'     => new sfWidgetFormInputText(),
      'website'     => new sfWidgetFormInputText(),
      'title'       => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorPropelChoice(array('model' => 'Speaker', 'column' => 'id', 'required' => false)),
      'name'        => new sfValidatorString(array('max_length' => 50)),
      'lastname'    => new sfValidatorString(array('max_length' => 50)),
      'sex'         => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647)),
      'birth_year'  => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647)),
      'email'       => new sfValidatorString(array('max_length' => 100)),
      'contact'     => new sfValidatorString(array('max_length' => 255)),
      'website'     => new sfValidatorString(array('max_length' => 150)),
      'title'       => new sfValidatorString(array('max_length' => 255)),
      'description' => new sfValidatorString(array('max_length' => 255)),
      'created_at'  => new sfValidatorDateTime(array('required' => false)),
      'updated_at'  => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('speaker[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Speaker';
  }


}
