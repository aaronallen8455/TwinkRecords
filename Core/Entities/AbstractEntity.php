<?php
/**
 * Created by PhpStorm.
 * User: Aaron Allen
 * Date: 6/12/2016
 * Time: 1:52 AM
 */

namespace Core\Entities;


use Core\DB\DB;

abstract class AbstractEntity
{
    const ID = null;
    const TABLE_NAME = null;
    
    /**
     * Magic getter and setter methods
     *
     * @param $name
     * @param $arguments
     * @return mixed|$this|null
     */
    public function __call($name, $arguments)
    {
        // Get or Set
        $method = substr($name, 0, 3);
        // Property name
        $prop = substr($name, 3);
        // convert from camel case
        $prop = preg_split('/(?<!^)(?=[A-Z])/', $prop);
        $prop = implode('_', $prop);
        $prop = strtolower($prop);

        switch ($method) {
            case 'get':
                return $this->$prop;
            case 'set':
                $this->$prop = $arguments[0];
                return $this;
        }
        return null;
    }

    /**
     * Set model fields
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $props = get_object_vars($this);
        foreach ($data as $item=>$value) {
            if (in_array($item, $props)) {
                $this->$item = $value;
            }
        }
        return $this;
    }

    /**
     * Get data
     *
     * @return array
     */
    abstract public function getData();

    /**
     * Get the id value
     *
     * @return int
     */
    public function getId()
    {
        $idField = $this::ID;
        return $this->$idField;
    }

    /**
     * Delete the model
     *
     * @return bool
     */
    public function delete()
    {
        $db = DB::getConnection();
        return $db->query('DELETE FROM ' . $this::TABLE_NAME . ' WHERE ' . $this::ID . '=' . $this->getId());
    }

    /**
     * Save the model
     *
     * @return bool
     */
    public function save()
    {
        $db = DB::getConnection();
        $data = $this->getData();

        //check for id to determine if model needs to be inserted or updated
        if (isset($data[$this::ID])) {
            $sql = "UPDATE " . $this::TABLE_NAME . " SET ";
            foreach ($data as $item => $value) {
                if ($item === $this->getId()) continue;
                $sql .= '`$item`' . "='$value' ";
            }
            $sql .= "WHERE `" . $this::ID . "`={$this->getId()}";
        }else{
            //get rid of empty ID field
            unset($data[$this::ID]);
            $sql = "INSERT INTO " . $this::TABLE_NAME . " (";
            foreach ($data as $item => $value) {
                $sql .= "`$item`, ";
            }
            $sql = substr($sql, 0, -2) . ") VALUES (";
            foreach ($data as $value) {
                $sql .= "'$value'" . ', ';
            }
            $sql = substr($sql, 0, -2) . ")";
        }
        return $db->query($sql);
    }

    /**
     * load specific entity
     * 
     * @param $id
     * @return AbstractEntity
     */
    public function load($id)
    {
        $db = DB::getConnection();
        $stmt = $db->query("SELECT * FROM " . $this::TABLE_NAME . " WHERE `" . $this::ID . "`=$id");
        $data = $stmt->fetch_assoc();
        return $this->setData($data);
    }

    /**
     * Parse input data
     *
     * @param array $data
     * @param array $errors
     * @return array
     */
    public function prepareData(array $data, array &$errors)
    {
        return $data;
    }

    /**
     * Get incomplete fields errors
     * 
     * @param array $data
     * @param array $errors
     * @return array
     */
    protected function checkDataCompletion(array $data, array $errors)
    {
        foreach (get_class_vars($this) as $prop) {
            $errors[$prop] = !isset($data[$prop]);
        }
        $errors[$this::ID] = false; // assigned on creation
        
        return $errors;
    }
}