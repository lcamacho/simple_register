<?php

/**
 * Attendee filter form base class.
 *
 * @package    web_app
 * @subpackage filter
 * @author     Leonard Camacho <leonard.camacho@gmail.com>
 */
abstract class BaseAttendeeFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lastname'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sex'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'birth_year' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'email'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'known_by'   => new sfWidgetFormFilterInput(),
      'comments'   => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'name'       => new sfValidatorPass(array('required' => false)),
      'lastname'   => new sfValidatorPass(array('required' => false)),
      'sex'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'birth_year' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'email'      => new sfValidatorPass(array('required' => false)),
      'known_by'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comments'   => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('attendee_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Attendee';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'name'       => 'Text',
      'lastname'   => 'Text',
      'sex'        => 'Number',
      'birth_year' => 'Number',
      'email'      => 'Text',
      'known_by'   => 'Number',
      'comments'   => 'Text',
      'created_at' => 'Date',
    );
  }
}
