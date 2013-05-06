<?php

class spAdminObjectController extends spController
{
  protected $_object_class_name;
  protected $_import_fields = array();
  protected $_allow_create = true;
  protected $_allow_delete = true;
  protected $_skip_validation = false;

  function doDisplay()
  {
    $this->useForm('filter_form');
    $this->setFormDataSource($this->request);
    $this->view->set('items', $this->_fetchItems());
  }

  protected function _fetchItems()
  {
    return $this->_createQuery()->fetch();
  }

  protected function _createQuery()
  {
    $query = lmbARQuery :: create($this->_object_class_name);
    return $query;
  }

  function doAjaxEditForm()
  {
    if(!$this->item = $this->_findItem(false))
      return $this->sendBadRequest();
    $this->useForm('item_form');
    $this->setFormDataSource($this->item);
    $this->view->set('item', $this->item);
    $this->view->set('import_fields', $this->_import_fields);
    $this->sendAjaxHtml();
  }

  function doAjaxSave()
  {
    if(!$this->item = $this->_findItem(true))
      return $this->sendBadRequest();
    foreach($this->_import_fields as $field)
    {
      $value = $this->request->getPost($field);
      $this->item->set($field, $value === null ? '' : $value);
    }
    if(true !== $this->_skip_validation)
    {
      if(!$this->item->validate($this->error_list))
        return $this->sendAjaxErrorList();
    }
    $this->item->saveSkipValidation();
    $this->_sendAjaxRowTable($this->item);
  }

  protected function _sendAjaxRowTable($item, $template_action = 'display')
  {
    $this->setTemplate($this->findTemplateForAction($template_action));
    $this->view->set('items', new lmbCollection(array($item)));
    $this->view->set('toolkit', $this->toolkit);
    $this->sendAjaxSlots('rows', array('id' => $item->id));
  }

  function doAjaxDelete()
  {
    if(!$this->_allow_delete || (!$this->item = $this->_findItem(true)))
      return $this->sendBadRequest();
    $this->item->destroy();
    $this->sendAjaxSuccess();
  }

  function doAjaxPublish()
  {
    if(!$this->_allow_delete || (!$this->item = $this->_findItem(true)) || !$this->item->has("is_published"))
      return $this->sendBadRequest();
    $this->item->set("is_published", $this->request->getInteger("publish"));
    $this->item->saveSkipValidation();
    $this->sendAjaxValue("publish", $this->item->get("is_published"));
  }

  protected function _findItem($required_post)
  {
    if($required_post && !$this->request->hasPost())
      return false;
    if($this->request->getInteger('is_create'))
    {
      if($this->_allow_create)
        return $this->_createObject();
      return false;
    }
    return $this->getObjectByRequestedId($this->_object_class_name);
  }

  protected function _createObject()
  {
    return new $this->_object_class_name;
  }
}
