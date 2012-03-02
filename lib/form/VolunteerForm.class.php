<?php

/**
 * Volunteer form.
 *
 * @package    web_app
 * @subpackage form
 * @author     Leonard Camacho <leonard.camacho@gmail.com>
 */
class VolunteerForm extends BaseVolunteerForm
{
  public function configure()
  {
    unset(
      $this['id'],
      $this['created_at'],
      $this['updated_at']
    );

    $this->widgetSchema['name'] = new sfWidgetFormInputText(array('label' => 'Nombre'));
    $this->widgetSchema['lastname'] = new sfWidgetFormInputText(array('label' => 'Apellido'));
    $this->widgetSchema['contact'] = new sfWidgetFormInputText(array('label' => 'Contacto'));
    $this->widgetSchema['website'] = new sfWidgetFormInputText(array('label' => 'Sitio Web'));
    
    $this->widgetSchema['sex'] = new sfWidgetFormChoice(array(
      'choices' => array('Hombre', 'Mujer'),
      'label' => 'Sexo de nacimiento'
    ));

    $this->widgetSchema['birth_year'] =  new sfWidgetFormChoice(array(
      'choices' => range(2011, 1910, 1),
      'label' => 'Año de nacimiento'
    ));

    $this->widgetSchema['group'] = new sfWidgetFormChoice(array(
      'choices' => array('Logistica', 'Protocolo'),
      'label' => 'Grupo'
    ));

    sfValidatorBase::setDefaultMessage('required', 'Este campo es requerido.');
    sfValidatorBase::setDefaultMessage('invalid', 'Por favor revisa este campo.');

    $this->validatorSchema['name'] = new sfValidatorAnd(array(
      $this->validatorSchema['name'],
      new sfValidatorString(array('required' => true))
    ));

    $this->validatorSchema['lastname'] = new sfValidatorAnd(array(
      $this->validatorSchema['lastname'],
      new sfValidatorString(array('required' => true))
    ));

    $this->validatorSchema['email'] = new sfValidatorAnd(array(
      $this->validatorSchema['email'],
      new sfValidatorEmail(array('required' => true))
    ));

    $this->validatorSchema['contact'] = new sfValidatorAnd(array(
      $this->validatorSchema['contact'],
      new sfValidatorString(array('required'=>true))
    ));
      
    $this->validatorSchema['website'] = new sfValidatorAnd(array(
      $this->validatorSchema['website'],
      new sfValidatorUrl(array('required' => true))
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(
        array(
          'model' => 'Volunteer',
          'column' => array('email'),
          'throw_global_error' => true
        ),
        array(
          'invalid' => 'Ya existe un usuario registrado con este correo.'
        )
      )
    );
  }
}
