<?php

final class DiffusionRepositorySymbolsManagementPanel
  extends DiffusionRepositoryManagementPanel {

  const PANELKEY = 'symbols';

  public function getManagementPanelLabel() {
    return pht('Symbols');
  }

  public function getManagementPanelOrder() {
    return 900;
  }

  protected function buildManagementPanelActions() {
    $repository = $this->getRepository();
    $viewer = $this->getViewer();

    $can_edit = PhabricatorPolicyFilter::hasCapability(
      $viewer,
      $repository,
      PhabricatorPolicyCapability::CAN_EDIT);

    $symbols_uri = $repository->getPathURI('edit/symbols/');

    return array(
      id(new PhabricatorActionView())
        ->setIcon('fa-pencil')
        ->setName(pht('Edit Symbols'))
        ->setHref($symbols_uri)
        ->setDisabled(!$can_edit)
        ->setWorkflow(!$can_edit),
    );
  }

  public function buildManagementPanelContent() {
    $repository = $this->getRepository();
    $viewer = $this->getViewer();

    $view = id(new PHUIPropertyListView())
      ->setViewer($viewer)
      ->setActionList($this->newActions());

    $languages = $repository->getSymbolLanguages();
    if ($languages) {
      $languages = implode(', ', $languages);
    } else {
      $languages = phutil_tag('em', array(), pht('Any'));
    }
    $view->addProperty(pht('Languages'), $languages);

    $sources = $repository->getSymbolSources();
    if ($sources) {
      $sources = $viewer->renderHandleList($sources);
    } else {
      $sources = phutil_tag('em', array(), pht('This Repository Only'));
    }
    $view->addProperty(pht('Uses Symbols From'), $sources);

    return $this->newBox(pht('Symbols'), $view);
  }

}
