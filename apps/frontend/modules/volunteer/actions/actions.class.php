<?php

/**
 * volunteer actions.
 *
 * @package    web_app
 * @subpackage volunteer
 * @author     Leonard Camacho <leonard.camacho@gmail.com>
 */
class volunteerActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new volunteerForm();
    $this->setTemplate('new');
  }

  public function executeComplete(sfWebRequest $request)
  {
    $this->volunteer = VolunteerPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($this->volunteer);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new volunteerForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($volunteer = VolunteerPeer::retrieveByPk($request->getParameter('id')), sprintf('Object volunteer does not exist (%s).', $request->getParameter('id')));
    $this->form = new volunteerForm($volunteer);
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $volunteer = $form->save();

      $this->redirect('volunteer/complete?id='.$volunteer->getId());
    }
  }
}
