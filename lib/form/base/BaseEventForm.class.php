<?php

/**
 * Event form base class.
 *
 * @method Event getObject() Returns the current form's model object
 *
 * @package    web_app
 * @subpackage form
 * @author     Leonard Camacho <leonard.camacho@gmail.com>
 */
abstract class BaseEventForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormInputText(),
      'timezone'    => new sfWidgetFormInputText(),
      'start'       => new sfWidgetFormDateTime(),
      'end'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorPropelChoice(array('model' => 'Event', 'column' => 'id', 'required' => false)),
      'name'        => new sfValidatorString(array('max_length' => 255)),
      'description' => new sfValidatorString(array('max_length' => 255)),
      'timezone'    => new sfValidatorString(array('max_length' => 50)),
      'start'       => new sfValidatorDateTime(),
      'end'         => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('event[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Event';
  }


}
