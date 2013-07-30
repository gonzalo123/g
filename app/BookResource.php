<?php

class BookResource
{
    public function getAction($id)
    {
        return "BookResource:getAction " . $id;
    }

    public function getAllAction()
    {
        return "BookResource:getAllAction";
    }

    public function saveAction($id)
    {
        return "BookResource:saveAction " . $id;
    }

    public function addAction()
    {
        return "BookResource:addAction";
    }

    public function deleteAction($id)
    {
        return "BookResource:deleteAction " . $id;
    }
}