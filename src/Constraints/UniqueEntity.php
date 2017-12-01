<?php

namespace Constraints;

use Symfony\Component\Validator\Constraint;
/**
 * Description of UnqiueEntity
 *
 * @author Etudiant
 */
class UniqueEntity extends Constraint
{
    public $message = 'The username already exists';
    
    public $field;
    public $dao;
    
    public function __construct($options = null)
    {
        if(is_array($options)){
            $this->field = $options['field'];
            $this->dao = $options['dao'];
        }
        parent::__construct($options);
    }
    
    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
    public function getField()
    {
        return $this->field;
    }

    public function getDao()
    {
        return $this->dao;
    }


}
