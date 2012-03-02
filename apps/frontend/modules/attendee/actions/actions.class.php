<?php

/**
 * attendee actions.
 *
 * @package    web_app
 * @subpackage attendee
 * @author     Leonard Camacho <leonard.camacho@gmail.com>
 */
class attendeeActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new attendeeForm();

    $this->setTemplate('new');
  }

  public function executeComplete(sfWebRequest $request)
  {
   
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new attendeeForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($attendee = AttendeePeer::retrieveByPk($request->getParameter('id')), sprintf('Object attendee does not exist (%s).', $request->getParameter('id')));
    $this->form = new attendeeForm($attendee);
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $attendee = $form->save();

      $this->redirect('attendee/complete');
    }
  }
}
