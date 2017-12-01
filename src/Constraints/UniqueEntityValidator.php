<?php

namespace Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Constraints\UniqueEntity;
/**
 * Description of UniqueEntityValidator
 *
 * @author Etudiant
 */
class UniqueEntityValidator extends ConstraintValidator
{
    
    public function validate($value, UniqueEntity $constraint)
    {
        $field = $constraint->getField();
        $dao = $constraint->getDao();
        
        $entity = $dao->findOne(["$field = ?" => $value]);
        
        if($entity){
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }

}
