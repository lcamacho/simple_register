<?php

/**
 * speaker actions.
 *
 * @package    web_app
 * @subpackage speaker
 * @author     Leonard Camacho <leonard.camacho@gmail.com>
 */
class speakerActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new speakerForm();
    $this->setTemplate('new');
  }

  public function executeComplete(sfWebRequest $request)
  {
    $this->speaker = SpeakerPeer::retrieveByPk($request->getParameter('id'));
    $this->forward404Unless($this->speaker);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new speakerForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($speaker = SpeakerPeer::retrieveByPk($request->getParameter('id')), sprintf('Object speaker does not exist (%s).', $request->getParameter('id')));
    $this->form = new speakerForm($speaker);
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $speaker = $form->save();

      $this->redirect('speaker/complete?id='.$speaker->getId());
    }
  }
}
