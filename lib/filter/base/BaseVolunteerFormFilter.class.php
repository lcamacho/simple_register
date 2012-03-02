<?php

/**
 * Volunteer filter form base class.
 *
 * @package    web_app
 * @subpackage filter
 * @author     Leonard Camacho <leonard.camacho@gmail.com>
 */
abstract class BaseVolunteerFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'lastname'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sex'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'birth_year' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'email'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'contact'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'website'    => new sfWidgetFormFilterInput(),
      'group'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'name'       => new sfValidatorPass(array('required' => false)),
      'lastname'   => new sfValidatorPass(array('required' => false)),
      'sex'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'birth_year' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'email'      => new sfValidatorPass(array('required' => false)),
      'contact'    => new sfValidatorPass(array('required' => false)),
      'website'    => new sfValidatorPass(array('required' => false)),
      'group'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('volunteer_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Volunteer';
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
      'contact'    => 'Text',
      'website'    => 'Text',
      'group'      => 'Number',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
