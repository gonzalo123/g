<?php
use G\RESTFull\Get;
use G\RESTFull\GetAll;
use G\RESTFull\Delete;
use G\RESTFull\Create;
use G\RESTFull\Update;

class BookResource implements Get, GetAll, Delete, Create, Update
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