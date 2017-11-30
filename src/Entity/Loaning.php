<?php

namespace Entity;

/**
 * Description of Loaning
 *
 * @author Etudiant
 */
class Loaning
{
    /**
     *
     * @var integer 
     */
    private $id;
    
    /**
     *
     * @var \Datetime
     */
    private $dateStart;
    
    /**
     *
     * @var \Datetime 
     */
    private $dateEnd;
    
    /**
     * The user that did this loaning
     * @var \Entity\User
     */
    private $user;
    
    /**
     * The game associated with this loaning
     * @var \Entity\Game  
     */
    private $game;
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getDateStart()
    {
        return $this->dateStart;
    }

    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setDateStart($dateStart)
    {
        if($dateStart instanceof string) {
            $dateStart = \DateTime::createFromFormat('Y/m/d', $dateStart);
        }
        $this->dateStart = $dateStart;
    }

    public function setDateEnd($dateEnd)
    {
        if($dateEnd instanceof string) {
            $dateEnd = \DateTime::createFromFormat('Y/m/d', $dateEnd);
        }
        $this->dateEnd = $dateEnd;
    }

    public function setUser(\Entity\User $user)
    {
        $this->user = $user;
    }

    public function setGame(\Entity\Game $game)
    {
        $this->game = $game;
    }


}
